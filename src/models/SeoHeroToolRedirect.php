<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Control\Director;

class SeoHeroToolRedirect extends DataObject
{
    private static $table_name = 'SeoHeroToolRedirect';
    private static $db = array(
      'OldLinkName' => 'Varchar(200)',
      'NewLinkName' => 'Varchar(200)'
    );

    private static $has_one = array(
      'NewLink' => 'SiteTree',
      'SeoHeroToolEditFile' => 'SeoHeroToolEditFile',
    );

    private static $singular_name = '301 Redirect';

    private static $summary_fields = array(
      'OldLinkName' =>  'Old Link'
    );

    /*private static $indexes = array(
      'OldLinkNameIndex'=> array(
        'type' => 'unique',
        'value' => 'OldLinkName'
      )
    );*/

    public $debug = false;
    public static $has_written = false;

    public function getCMSFields()
    {
        $fields = parent::getcmsFields();
        $fields->addFieldToTab('Root.Main', new TextField('OldLinkName', _t('SeoHeroToolRedirect.OldLink', 'Old Link')));
        $fields->addFieldToTab('Root.Main', new TreeDropdownField("NewLinkID", _t('SeoHeroToolRedirect.NewLink', 'New Link'), "SiteTree"));
        $fields->addFieldToTab('Root.Main', new TextField('NewLinkName', _t('SeoHeroToolRedirect.NewLink', 'or enter the New Link directly')));
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (isset($this->OldLinkName) && $this->NewLinkName) {
            $this->NewLinkName = $this->addSlashToLink($this->NewLinkName);
        }
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        $this->writeFile();
    }

    public function onAfterDelete()
    {
        parent::onAfterDelete();
        $this->writeFile();
        return;
    }

    private function addSlashToLink($Link)
    {
        if ($Link[0] != '/') {
            $Link = '/'.$Link;
        }
        return $Link;
    }

    public function writeFile($testNewFile = false, $OldLinkTest = false, $NewLinkTest = false)
    {
        $htaccessFile = $_SERVER["DOCUMENT_ROOT"].'/.htaccess';
        $htaccesscontent = file_get_contents($htaccessFile, FILE_USE_INCLUDE_PATH);
        $redirect = SeoHeroToolRedirect::get();
        $redirectString = "Redirect 301";

        $content = "### SEOHEROTOOL Redirects ### \n";
        $redirecturl = Director::protocolAndHost();

        foreach ($redirect as $link) {
            if (isset($link->OldLinkName) && $link->OldLinkName != $OldLinkTest) {
                if ($link->NewLinkID) {
                    # if link is set via sitetree
                    $newLink = $redirecturl.$link->NewLink()->Link();
                    $content .= "$redirectString $link->OldLinkName $newLink \n";
                } elseif (isset($link->NewLinkName)) {
                    # if link is set via direct link
                    $newLink = $redirecturl.$link->NewLinkName;
                    $content .= "$redirectString $link->OldLinkName $newLink \n";
                }
            }
        }

        if ($testNewFile) {
            $newLink = $redirecturl.$NewLinkTest;
            $content .= "$redirectString $OldLinkTest $newLink \n";
        }

        $content .= "### SEOHEROTOOL Redirects ###";

        if ($testNewFile) {
            $htaccessFile = $_SERVER["DOCUMENT_ROOT"].'/'.SEO_HERO_TOOL_PATH .'/htaccess-test/.htaccess';
        }


        echo "No Debug Mode detected<br/>";
        if (strpos($htaccesscontent, "### SEOHEROTOOL Redirects ###") !== false) {
            $explode = explode("### SEOHEROTOOL Redirects ###", $htaccesscontent);
            file_put_contents($htaccessFile, $explode[0]. $content . $explode[2]);
        } else {
            file_put_contents($htaccessFile, $content . $htaccesscontent);
        }
    }

    private function writeHtaccessTest()
    {
        if ($this->NewLink()->URLSegment != '') {
            $NewLink = $this->NewLink()->URLSegment;
        } else {
            $NewLink = $this->NewLinkName;
        }
        $NewLink = $this->addSlashToLink($NewLink);
        $this->writeFile(true, $this->OldLinkName, $NewLink);
        $url = Director::protocolAndHost().'/'.SEO_HERO_TOOL_PATH.'/htaccess-test/index.php';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);
        return $httpCode;
    }

    public function validate()
    {
        $result = parent::validate();
        # OldLinkName has to be set
        if (!$this->OldLinkName) {
            $result->error(_t('SeoHeroToolRedirect.OldLinkNameHasToBeSet', 'Old Link has to be set and can not be empty'));
        }

        # OldLinkName is not allowed to contain a space
        if (strpos($this->OldLinkName, ' ') !== false) {
            $result->error(_t('SeoHeroToolRedirect.OldLinkNameHasSpace', 'Old Link contains a space which is not allowed here, please mask any space with the correct HTML character!'));
        }

        # NewLinkName is not allowed to contain a space
        if (strpos($this->NewLinkName, ' ') !== false) {
            $result->error(_t('SeoHeroToolRedirect.NewLinkNameHasSpace', 'New Link contains a space which is not allowed here, please mask any space with the correct HTML character!'));
        }

        # it is not allowed that NewLinkName and NewLink() are both empty
        if ($this->NewLinkName == '' && $this->NewLink()->ID == '') {
            $result->error(_t('SeoHeroToolRedirect.NewLinkOrLinkNeedsToBeSet', 'Either the New Link or the Select needs to be choosen.'));
        }

        # Check if OldLinkName is already in use and has a different ID (unique constraint)
        $redirect = SeoHeroToolRedirect::get()->Filter(array('OldLinkName'=>$this->OldLinkName))->Limit(1);
        foreach ($redirect as $r) {
            if (isset($r->ID) && $r->ID != $this->ID) {
                $result->error(_t('SeoHeroToolRedirect.LinkAlreadyInUse', 'Old Link has already a redirect, please check the link.'));
            }
        }

        # Check .htaccess in htaccess-test folder delivers status code 200, otherwise we might have an issue with the .htaccess
        $httpCode = $this->writeHtaccessTest();
        if ($httpCode != '200') {
            $result->error(_t('SeoHeroToolRedirect.TestStatusCodeIsBad', 'The Status Code of the .htaccess test is not 200, but '.$httpCode.'. Changes were not saved to keep the website working'));
        }
        return $result;
    }
}

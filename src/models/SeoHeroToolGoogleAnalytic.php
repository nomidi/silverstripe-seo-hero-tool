<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DB;
use SilverStripe\Security\Permission;

class SeoHeroToolGoogleAnalytic extends DataObject
{
    private static $table_name = 'SeoHeroToolGoogleAnalytic';
    private static $db = array(
      'AnalyticsKey' => 'Text',
      'UserOptOut' => 'Boolean',
      'AnonymizeIp' => 'Boolean',
      'LoadTime' => 'Boolean',
      'ActivateInMode' => "Enum('dev, live, test, All', 'live')",
    );

    private static $defaults = array(
      'UserOptOut' => true,
      'AnonymizeIp' => true,
      'ActivateInMode' => 'All'
    );

    private static $singular_name = 'Google Analytics';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new TextField('AnalyticsKey', _t('SeoHeroToolGoogleAnalytic.AnalyticsKey', 'Google Analytics Key')));
        $fields->addFieldToTab('Root.Main', new CheckboxField('UserOptOut', _t('SeoHeroToolGoogleAnalytic.UserOptOut', 'User Opt-out?')));
        $fields->addFieldToTab('Root.Main', new CheckboxField('AnonymizeIp', _t('SeoHeroToolGoogleAnalytic.AnonymizeIp', 'Anonymize IP Adress?')));
        $fields->addFieldToTab('Root.Main', new CheckboxField('LoadTime', _t('SeoHeroToolGoogleAnalytic.LoadTime', 'Record Loading Time?')));
        $fields->addFieldToTab('Root.Main', new DropdownField('ActivateInMode', _t('SeoHeroToolGoogleAnalytic.ActivateInMode', 'Activate when Site is in Mode'), $this->dbObject('ActivateInMode')->enumValues()));

       /* $env_type = Config::inst()->get('Director', 'environment_type');
        if ($env_type == $this->ActivateInMode || $this->ActivateInMode == 'All') {
            $matchString = _t('SeoHeroToolGoogleAnalytic.ActualModeMatchEnvironment', 'Your actual Environment mode does match the Settings. Google Anayltics should be working.');
        } else {
            $matchString = _t('SeoHeroToolGoogleAnalytic.ActualModeDoesNotMatchEnvironment', 'Your actual Environment mode does not Match the Settings. Google Analytics will not work.');
        }*/
        $fields->addFieldToTab('Root.Main', new LiteralField('DoesModeMatch', $matchString), 'ActivateInMode');

        return $fields;
    }

    /*public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        return $form;
    }*/

    public function InitAnalytics()
    {
        /*if (
            (SS_ENVIRONMENT_TYPE == $this->ActivateInMode || $this->ActivateInMode == 'All') &&
        strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
        strpos($_SERVER['REQUEST_URI'], '/Security') === false) {
        return true;
        }
        return false;
        */
        return true;
    }

    public static function current_entry()
    {
        if ($entry = DataObject::get_one(SeoHeroToolGoogleAnalytic::class)) {
            return $entry;
        }
        return self::make_entry();
    }


    public static function make_entry()
    {
        $config = SeoHeroToolGoogleAnalytic::create();
        $config->write();

        return $config;
    }

  /**
     * Setup a default SiteConfig record if none exists
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $entry = DataObject::get_one(SeoHeroToolGoogleAnalytic::class);
        if (!$entry) {
            self::make_entry();
            DB::alteration_message("Added default SeoHeroToolGoogleAnalytic", "created");
        }
    }

    /**
     * Disable the creation of new records, as a site should have just one!
     * @param  [type] $Member The logged in Member^
     * @return [type]         Always return false to disallow any creation.
     */
    public function canCreate($member = null, $context = [])
    {
        if (permission::check('SUPERUSER')) {
            return false;
        } else {
            return false;
        }
    }

    /**
     * Disable the Deletion of records, as a site should always have one!
     * @param  [type] $Member The lggef in Member
     * @return [type]         Always return false to disallow any deletion
     */
    public function canDelete($Member = null)
    {
        if (permission::check('SUPERUSER')) {
            return false;
        } else {
            return false;
        }
    }
}

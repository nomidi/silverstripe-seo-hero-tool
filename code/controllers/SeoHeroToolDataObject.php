<?php

class SeoHeroToolDataObject extends DataExtension
{
    private static $db = array(
        'BetterTitle' => 'Varchar(255)',
    );

    /*
    *   Function MetaTitle() overwrites the default title. If BetterTitle is set,
    *   then this will be used. Otherwise it will check the if there is a
    *   yml file for this. If this is also not the case, the default
    *   title will be returned
    */

    public function MetaTitle()
    {
        // check for BetterTitle
        if (isset($this->owner->BetterTitle) && $this->owner->BetterTitle != '') {
            return $this->owner->BetterTitle;
        }

        // Check for YAML Configuration
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
        //debug::show($yamlsettings);
        if ($yamlsettings) {
            $return = $this->checkYAMLSettings($yamlsettings);
          //  debug::show($return);
            return $return;
        } else {
            // If no BetterTitle is set and no Title is set via configuration
          return $this->owner->Title;
        }
    }

    public function checkYAMLSettings($entry)
    {
        if (isset($entry)) {
            $return = '';
            if (isset($entry['WithoutSpace'])) {
                if ($entry['WithoutSpace']) {
                    $spacer = '';
                } else {
                    $spacer = ' ';
                }
            } else {
                $spacer = ' ';
            }

            $titleList = $entry['Title'];
            for ($i = 0; $i < count($titleList); $i++) {
                $obj = $this->owner->obj($titleList[$i]);
                $content = $obj->Value;
                $dataobject = $this->owner->obj($titleList[$i])->__get('class');

                if ($dataobject == 'SS_Datetime' || $dataobject == 'SS_Date') {
                    if (isset($entry['DateFormat'])) {
                        $formatOption = $entry['DateFormat'];
                    } else {
                        $formatOption = '';
                    }
                    switch ($formatOption) {
                      case 'Nice24':
                        $content = $obj->Nice24();
                        break;
                      case 'Year':
                        $content = $obj->Year();
                        break;
                      case 'Nice':
                        $content = $obj->Nice();
                        break;
                      default:
                        $content = $obj->Date();
                      }
                }

                if ($i == 0) {
                    $return = $content;
                } else {
                    $return .= $spacer.$content;
                }
            }
            if (isset($entry['SiteConfigTitle']) && $entry['SiteConfigTitle']) {
                $siteconfig = SiteConfig::current_site_config();
                $return .= ' '.$siteconfig->Title;
            }
            return $return;
        }
        return false;
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.SeoHeroTool', new TextField('BetterTitle', _t('SeoHeroTool.BetterTitle', 'BetterTitle')));

        return $fields;
    }
}

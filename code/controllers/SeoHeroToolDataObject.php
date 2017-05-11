<?php

class SeoHeroToolDataObject extends DataExtension
{
    private static $db = array(
        'BetterSiteTitle' => 'Varchar(255)',
        'Keyword' => 'Text',
        'FeaturedKeyword' => 'Text',
        'KeywordQuestion' => 'Text',
        'Follow' => "Enum('if,in,i,nf,nn,n','if')",
        'FollowType' => 'Boolean',
        'Canonical' => 'Text',
        'CanonicalAll' => 'Boolean',
        'GenMetaDesc' => 'Text'
    );

    private static $has_one = array(
        'CanonicalLink' => 'SiteTree',
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
        if (isset($this->owner->BetterSiteTitle) && $this->owner->BetterSiteTitle != null) {
            return $this->owner->BetterSiteTitle;
        }

        // Check for YAML Configuration
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
        if ($yamlsettings) {
            $return = $this->checkYAMLSettings($yamlsettings);

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
                        if ($entry['DateFormat'] == 'Specific' && isset($entry['DateFormatting'])) {
                            $formatOption = 'Specific';
                        } else {
                            $formatOption = $entry['DateFormat'];
                        }
                    } else {
                        $formatOption = '';
                    }
                    switch ($formatOption) {
                      case 'SpecialFormat':
                        $content = $obj->Format($entry['DateFormatting']);
                        break;
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
        $fields->addFieldToTab('Root.SeoHeroTool', new TextField('BetterSiteTitle', _t('SeoHeroTool.BetterSiteTitle', 'BetterTitle')));
        $defaultValue = config::inst()->get('SeoHeroToolDataObject', $this->owner->ClassName);
        if ($defaultValue != '') {
            $fields->addFieldToTab('Root.SeoHeroTool', new LiteralField('', _t('SeoHeroTool.DefaultValue', 'Default Value for this Pagetype due to config file is: ').$this->checkYAMLSettings($defaultValue)));
        } elseif ($defaultValue == '' && $this->owner->BetterSiteTitle == null) {
            $fields->addFieldToTab('Root.SeoHeroTool', new LiteralField('', _t('SeoHeroTool.DefaultTitle', 'Site has no BetterTitle and no Default Value from the configuration, so the title will be displayed.')));
        }

        $keywordToggleField = ToggleCompositeField::create(
                'Keywords', 'Keywords',
                array(
                    $keywordField = TextField::create('FeaturedKeyword', _t('SeoHeroTool.FeaturedKeyword', 'Keywords')),
                    $keywordQuestionField = TextareaField::create('KeywordQuestion', _t('SeoHeroTool.KeywordQuestion', 'Fragestellungen')),

                )
            );

        $fields->addFieldToTab('Root.SeoHeroTool', $keywordToggleField);
        $keywordField->setRightTitle(
                _t('SeoHeroTool.FeaturedKeywordAfter', 'Using commas to separate Keywords..')
            );
        $keywordQuestionField->setRightTitle(
                _t('SeoHeroTool.KeywordQuestionAfter', 'This field saves questions from the W-Questions, available only in German right now.')
            );

        $SeoFollowFields = singleton('Page')->dbObject('Follow')->enumValues();
        $SeoFormArray = array();
        foreach ($SeoFollowFields as $SeoFollowFieldKey => $SeoFollowFieldVal) {
            switch ($SeoFollowFieldKey) {
                    case 'in':
                        $SeoFormArray['in'] = _t('SeoHeroTool.FOLLOW_IN', 'Index website, do not follow links (index, nofollow)');
                        break;
                    case 'i':
                        $SeoFormArray['i'] = _t('SeoHeroTool.FOLLOW_I', 'Index website, follow links (index)');
                        break;
                    case 'nf':
                        $SeoFormArray['nf'] = _t('SeoHeroTool.FOLLOW_NF', 'Do not index website, follow links (noindex, follow)');
                        break;
                    case 'nn':
                        $SeoFormArray['nn'] = _t('SeoHeroTool.FOLLOW_NN', 'Do not index website, do not follow links (noindex, nofollow)');
                        break;
                    case 'n':
                        $SeoFormArray['n'] = _t('SeoHeroTool.FOLLOW_N', 'Do not follow website, follow index (noindex)');
                        break;
                    default:
                        $SeoFormArray['if'] = _t('SeoHeroTool.FOLLOW_IF', 'Index website, follow links (index,follow)');
                        break;
                }
        }


        $meta = ToggleCompositeField::create(
                'MetaData', 'Meta Daten',
                array(
                    DropdownField::create('Follow', _t('SeoHeroTool.RobotsHeadline', 'Robots'), $SeoFormArray),
                    CheckboxField::create("FollowType", _t('SeoHeroTool.FollowType', 'Should the site inherit the settings from the parent site?')),
                    $canonicalField = TextField::create('Canonical', _t('SeoHeroTool.Canonical', 'Canonical URL'))
                        ->setRightTitle(_t('SeoHeroTool.CanonicalAfter', 'Canonical URL, only use it if you know what you are going to do.')),
                    $canonicalFieldSiteTree = new TreeDropdownField("CanonicalLinkID", "Choose Canonical URL from the SiteTree", "SiteTree"),
                    $canonicalFieldAll = CheckboxField::create('CanonicalAll', _t('SeoHeroTool.CanonicalAll', 'Add at the end of the Canonical URL all=all.')),
                    $metaDescField = TextareaField::create("MetaDescription", _t('SeoHeroTool.OwnMetaDesc', 'Meta description'))
                )
            );
        // Hide Silverstripe default Metadata and display instead our own MetaData
        $fields->removeFieldsFromTab('Root', array('Metadata'));
        $fields->addFieldToTab('Root.SeoHeroTool', $meta);
        return $fields;
    }

    public function BetterMetaDescription()
    {
        if ($this->owner->MetaDescription == '') {
            return $this->owner->GenMetaDesc;
        } elseif ($this->owner->MetaDescription != '') {
            return $this->owner->MetaDescription;
        } else {
            return false;
        }
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($this->owner->BetterSiteTitle == '') {
            $this->owner->BetterSiteTitle = null;
        }

        if ($this->owner->MetaDescription != '') {
            $genMetaDescription = substr(strip_tags(html_entity_decode($this->owner->Content)), 0, 140);

            $pos = strrpos($genMetaDescription, " ");
            if ($pos) {
                $genMetaDescription = substr($genMetaDescription, 0, $pos);
            }
            $this->owner->GenMetaDesc = $genMetaDescription;
        }
        if ($this->owner->FollowType == 1) {
            $this->owner->Follow = $this->owner->Parent()->Follow;
        }
    }
}

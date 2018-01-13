<?php

/**
 *  DataExtension which gives the ability to specify better Data for SEO, Twitter and Facebook
 *
 * @package : SeoHeroTool
 *
 */
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
        'GenMetaDesc' => 'Text',
        'FBTitle' => 'Varchar(80)',
        'FBDescription' => 'Text',
        'FBType' => "Enum('website, article, product','website')",
        'FBTypeOverride' => 'Boolean',
        'TwTitle' => 'Varchar(80)',
        'TwDescription' => 'Text',
    );

    private static $has_one = array(
        'CanonicalLink' => 'SiteTree',
        'FBImage' => 'Image',
        'TwImage' => 'Image',
    );

    public $current_meta_desc;

    public function CanonicalURL()
    {
        if (isset($this->owner->CanonicalAll) && $this->owner->CanonicalAll == 1) {
            $all = '?all=all';
        } else {
            $all = '';
        }

        if (isset($this->owner->Canonical) && $this->owner->Canonical != null) {
            return $this->owner->Canonical.$all;
        }

        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
        if ($yamlsettings) {
            $return = $this->checkCanonicalSettings($yamlsettings);
            if ($return) {
                return $return.$all;
            } else {
                return $this->owner->AbsoluteLink().$all;
            }
        } else {
            return $this->owner->AbsoluteLink().$all;
        }
    }

    public function checkCanonicalSettings($entry)
    {
        if (isset($entry)) {
            $return = '';
            if (isset($entry['Canonical'])) {
                $canon = $entry['Canonical'];
                if (!is_array($canon)) {
                    $canon = array($canon);
                }
                for ($i=0; $i< count($canon); $i++) {
                    $elementIsVariable = false;
                    if (substr($canon[$i], 0, 1) == '$') {
                        $actualElement = substr($canon[$i], 1);
                        $elementIsVariable = true;
                    } else {
                        $actualElement = $canon[$i];
                    }

                    if ($elementIsVariable) {
                        if (strpos($actualElement, '()')) {
                            $actualElement = substr($actualElement, 0, -2);
                            if (method_exists($this->owner->ClassName, $actualElement)) {
                                $content = $this->owner->{$actualElement}();
                            } else {
                                $content = '';
                            }
                        } elseif (strpos($actualElement, '.')) {
                            $HasOneArray = explode(".", $actualElement);
                            $object = $this->owner->{$HasOneArray[0]}();
                            if (isset($object->$HasOneArray[1]) && $object->ID != 0) {
                                $content = $object->$HasOneArray[1];
                            } else {
                                $content = '';
                            }
                        } else {
                            $obj = $this->owner->obj($actualElement);
                            $content = $obj->Value;
                        }
                    } else {
                        $content = $actualElement;
                    }
                    if ($i == 0) {
                        $return = $content;
                    } else {
                        $return .= $content;
                    }
                }
                return strip_tags($return);
            }
            return false;
        }
    }

    /**
     *
     *   Function MetaTitle() overwrites the default title. If BetterSiteTitle is set,
     *   then this will be used. Otherwise it will check the if there is a
     *   yml file for this. If this is also not the case, the default
     *   title will be returned
     *
     *   @return string       Title for this webpage
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
            if (isset($yamlsettings['Title'])) {
                $return = $this->checkTitleYAMLSettings($yamlsettings);
                return $return;
            } else {
                return $this->owner->Title;
            }
        } else {
            // If no BetterTitle is set and no Title is set via configuration
          return $this->owner->Title;
        }
    }

    /**
     * checkTitleYAMLSettings checks if there is a title configuration for the given classname
     * @param  array $entry array from the configuration file which contains the settings for this classname
     * @return string       The Title configuration from the yml file
     */
    public function checkTitleYAMLSettings($entry)
    {
        if (isset($entry) && isset($entry['Title'])) {
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
            if (! is_array($titleList)) {
                $titleList = array($titleList);
            }
            for ($i = 0; $i < count($titleList); $i++) {
                $elementIsVariable = false;
                if (substr($titleList[$i], 0, 1) == '$') {
                    $actualElement = substr($titleList[$i], 1);
                    $elementIsVariable = true;
                } else {
                    $actualElement = $titleList[$i];
                }


                if ($elementIsVariable) {
                    #Variable

                    if (strpos($actualElement, '()')) {
                        $actualElement = substr($actualElement, 0, -2);
                        if (method_exists($this->owner->ClassName, $actualElement)) {
                            $content = $this->owner->{$actualElement}();
                        } else {
                            $content = '';
                        }
                    } elseif (strpos($actualElement, '.')) {
                        #has-one connection
                        $HasOneArray = explode(".", $actualElement);
                        $object = $this->owner->{$HasOneArray[0]}();
                        if (isset($object->$HasOneArray[1]) && $object->ID != 0) {
                            $content = $object->$HasOneArray[1];
                        } else {
                            $content = '';
                        }
                    } else {
                        $obj = $this->owner->obj($actualElement);
                        $content = $obj->Value;

                        $dataobject = $this->owner->obj($actualElement)->__get('class');

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
                    }
                } else {
                    # es handelt sich um einen String
                    $content = $actualElement;
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
            return strip_tags($return);
        }
        return $this->owner->Title;
    }

    /**
     * udpateCMSFields updates the CMS Fields and adds the fields from the SeoHeroToolDataObject-Extension.
     *
     * @param FieldList $fields existing fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        Requirements::javascript('https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?autoload=true');
        # Snippet Preview
        $SEOPreview = $this->owner->customise(array(
          'Title' => $this->MetaTitle(),
          'AbsoluteLink' => $this->owner->AbsoluteLink,
          'FBImage' => $this->SMPreviewImage('FB'),
          'FBTitle' =>  $this->owner->FBTitle,
          'FBDescription'=> $this->owner->FBDescription,
          'TWImage' => $this->SMPreviewImage('TW'),
          'TWTitle' =>  $this->owner->TWTitle,
          'TWDescription'=> $this->owner->TWDescription,
          'Server'=> $_SERVER['SERVER_NAME'],
          'MetaDesc' => $this->BetterMetaDescription()))->renderWith('SeoHeroToolSnippetPreview');

        $SEOPreviewField = CompositeField::create(
          HeaderField::create('SeoHeroTool', _t('SeoHeroTool.SEOSnippetPreviewHeadline', 'Snippet Preview')),
          LiteralField::create('SeoPreviewLiteral', $SEOPreview)
        );

        $fields->addFieldToTab('Root.SeoHeroToolPreview', $SEOPreviewField);
        # BetterSiteTitle
        $fields->addFieldToTab('Root.SeoHeroTool', $bstitle = new TextField('BetterSiteTitle', _t('SeoHeroTool.BetterSiteTitle', 'SEO Title')));
        $defaultValue = config::inst()->get('SeoHeroToolDataObject', $this->owner->ClassName);
        if ($defaultValue != '') {
            $bstitle->setRightTitle(_t('SeoHeroTool.DefaultValue', 'Default Value for this Pagetype due to config file is: ').$this->checkTitleYAMLSettings($defaultValue));
        }
        $bstitle->setAttribute('placeholder', $this->MetaTitle());

        # Keywords
        $keywordToggleField = ToggleCompositeField::create(
                'Keywords', 'Keywords',
                array(
                    $keywordField = TextField::create('FeaturedKeyword', _t('SeoHeroTool.FeaturedKeyword', 'Keywords')),
                    $keywordQuestionField = TextareaField::create('KeywordQuestion', _t('SeoHeroTool.KeywordQuestion', 'Interrogation')),

                )
            );
        if (!defined('SEO_HERO_TOOL_ADVANCED_PATH')) {
            $advancedRemark = _t('SeoHeroTool.AdvancedVersionRemark', 'The Keywords and Keyword Question are just available in the Advanced Version!');
            $keywordQuestionField->setRightTitle($advancedRemark);
        } else {
            $keywordQuestionField->setRightTitle(
                     _t('SeoHeroTool.KeywordQuestionAfter', 'This field saves questions from the W-Questions, available only in German right now.')
                 );
        }
        $fields->addFieldToTab('Root.SeoHeroTool', $keywordToggleField);
        $keywordField->setRightTitle(
                _t('SeoHeroTool.FeaturedKeywordAfter', 'Using commas to separate Keywords..')
            );

        # translations href
        $langhrefField = "";
        $langhrefFieldLabel = "";
        if ($this->owner->Translations) {
            $langhrefFieldTranslations = "";

            foreach ($this->owner->Translations as $lang) {
                $langhrefFieldTranslations .= " " . '<link rel="alternate" hreflang="' . i18n::convert_rfc1766($lang->Locale) . '" href="' . $lang->AbsoluteLink() . '" />';
            }
            if ($langhrefFieldTranslations != "") {
                $langhrefField = '<link rel="alternate" hreflang="' . i18n::convert_rfc1766($this->owner->Locale) . '" href="' . $this->owner->AbsoluteLink() . '" />';
                $langhrefField .= "\r" . $langhrefFieldTranslations;
                $langhrefField = str_replace("<", "&lt;", $langhrefField);
                $langhrefField = str_replace("<", "&gt;", $langhrefField);
                $langhrefFieldLabel = LabelField::create("LangHrefField", 'langhref Attribut')->addExtraClass('left');
                $langhrefField = LiteralField::create("LangHrefField", '<pre class="prettyprint">' . $langhrefField . '</pre>');
            }
        }

        # json schema
        $schemaData = $this->getSchemaObject();

        # validation schema
        $schemaData =  preg_replace('(\$\w+)', '<span class="nocode" style="color:red;font-weight:bold">$0</span>', $schemaData);
        $schemaErrorOutput = "";


        # json google validator
        $googleSchemaValidatorLink = "https://search.google.com/structured-data/testing-tool?url=".urlencode($this->owner->AbsoluteLink());
        $googleSchemaLinkField = '<br> <a href="'.$googleSchemaValidatorLink.'" target="_blank">Test your Schema with Google Structured Data Testing Tool</a>.';

        if ($schemaData) {
            $schemaFieldContent = '<div class="field"><p>Google Schema Org Data</p>
          <pre class="prettyprint linenums:1">'.$schemaData.'</pre><br>'._t('SeoHeroTool.jsonschemaDataExplanation', 'If there is any red text above this means that either a variable or a connection was not resolveable. Please check your configuration.').'
          <br><p>'.$googleSchemaLinkField.'</p></div>
          ';
        } else {
            $schemaFieldContent = '<div class="field"><p>Google Schema Org Data</p>'.
          _t('SeoHeroTool.NoSchemaDataPresendOnThisSite', 'No Google Schema Org Data present on this website.').'</div>';
        }


        # Meta Datas
        $SeoFormArray = $this->getSeoFollowFields();
        $meta = ToggleCompositeField::create(
                'MetaData', 'Meta Data',
                array(
                    DropdownField::create('Follow', _t('SeoHeroTool.RobotsHeadline', 'Robots'), $SeoFormArray),
                    CheckboxField::create("FollowType", _t('SeoHeroTool.FollowType', 'Should the site inherit the settings from the parent site?')),
                    $canonicalField = TextField::create('Canonical', _t('SeoHeroTool.Canonical', 'Canonical URL'))
                        ->setRightTitle(_t('SeoHeroTool.CanonicalAfter', 'Canonical URL, only use it if you know what you are going to do.')),
                    $canonicalFieldSiteTree = new TreeDropdownField("CanonicalLinkID", "Choose Canonical URL from the SiteTree", "SiteTree"),
                    $canonicalFieldAll = CheckboxField::create('CanonicalAll', _t('SeoHeroTool.CanonicalAll', 'Add at the end of the Canonical URL all=all.')),
                    $metaDescField = TextareaField::create("MetaDescription", _t('SeoHeroTool.OwnMetaDesc', 'Meta description')),
                    $metaLangHrefField = CompositeField::create(
                        $langhrefFieldLabel,
                        $langhrefField
                        ),
                  $jsonSchemaField = LiteralField::create('SeoPreviewLiteral', $schemaFieldContent),
                )
            );
        $metaDescField->setRightTitle(_t('SeoHeroTool.MetaDescAfterInformation', 'The ideal length of the Meta Description is between 120 and 140 character.'));
        $metaDescField->setAttribute('placeholder', $this->BetterMetaDescription());

        # Facebook
        $FBFormArray = $this->getFBFormFields();
        #check config.yml fbimage
        if ($this->SMPreviewImage('FB') && !$this->owner->FBImage()->exists()) {
            $FBPreviewImage =  new LiteralField('FBPreviewImage', '<div class="field"><p>'._t('SeoHeroTool.AutoFBImage', 'This site has a facebook picture configured via the configuration').': <br><img src="'.$this->SMPreviewImage('FB').'" width="150px"></p></div>');
        } else {
            $FBPreviewImage = false;
        }
        $fb = ToggleCompositeField::create(
            'Facebook', 'Facebook',
            array(
                $fbtit = Textfield::create('FBTitle', _t('SeoHeroTool.FBTitle', 'Title for Facebook')),
                $FBPreviewImage,
                $fbimg = UploadField::create('FBImage', _t('SeoHeroTool.FBImage', 'Image for Facebook')),
                $fbtypedd = DropdownField::create('FBType', _t('SeoHerotool.FBType', 'Type of Site'), $FBFormArray),
                CheckboxField::create('FBTypeOverride', _t('SeoHeroTool.FBTypeOverride', 'Overturn config setting')),
                $fbdesc = TextareaField::create('FBDescription', _t('SeoHeroTool.FBDescription', 'Description for Facebook')),
            )
        );
        $checkConfigForFBType = $this->getFBTypeFromConfig();
        if ($checkConfigForFBType) {
            $fbtypesentence = _t('SeoHeroTool.FBConfigExists', 'There is a value for this page type in the configuration which is:').$checkConfigForFBType;
            $fbtypedd->setRightTitle($fbtypesentence);
        }
        $imgFilesize = 2 * 1024 * 1024;
        $fbimg->getValidator()->setAllowedMaxFileSize($imgFilesize);
        $fbimg->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
        $fbimg->setFolderName('social-media-images');


        $fbtit->setAttribute('placeholder', $this->MetaTitle());
        $fbdesc->setAttribute('placeholder', $this->BetterMetaDescription());

        # Twitter
        if ($this->SMPreviewImage('FB') && !$this->owner->TWImage()->exists()) {
            $TWPreviewImage =  new LiteralField('FBPreviewImage', '<div class="field"><p>'._t('SeoHeroTool.AutoTWImage', 'This site has a twitter picture configured via the configuration').': <br><img src="'.$this->SMPreviewImage('FB').'" width="150px"></p></div>');
        } else {
            $TWPreviewImage = false;
        }
        $tw = ToggleCompositeField::create(
            'Twitter', 'Twitter',
            array(
                $twtit = Textfield::create('TwTitle', _t('SeoHeroTool.TwTitle', 'Title for Twitter')),
                $TWPreviewImage,
                $twimg = UploadField::create('TwImage', _t('SeoHeroTool.TwImage', 'Image for Twitter')),
                $twdesc = TextareaField::create('TwDescription', _t('SeoHeroTool.TwDescription', 'Description for Twitter')),
            )
        );
        $twimg->getValidator()->setAllowedMaxFileSize($imgFilesize);
        $twimg->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
        $twimg->setFolderName('social-media-images');
        $twtit->setAttribute('placeholder', $this->MetaTitle());
        $twdesc->setAttribute('placeholder', $this->BetterMetaDescription());

        // Hide Silverstripe default Metadata and display instead our own MetaData
        $fields->removeFieldsFromTab('Root', array('Metadata'));
        $fields->addFieldToTab('Root.SeoHeroTool', $meta);
        $fields->addFieldToTab('Root.SeoHeroTool', $fb);
        $fields->addFieldToTab('Root.SeoHeroTool', $tw);
        return $fields;
    }

    private function getSchemaObject()
    {
        if ($this->owner->hasExtension('SeoHeroToolSchemaDataObject')) {
            return $this->owner->getDisplayForBackend();
        }
        return false;
    }

    public function checkBetterMetaDescriptionYaml($entry)
    {
        if (!is_array($entry)) {
            $entry = array($entry);
        }
        for ($i=0; $i< count($entry); $i++) {
            $elementIsVariable = false;
            if (substr($entry[$i], 0, 1) == '$') {
                $actualElement = substr($entry[$i], 1);
                $elementIsVariable = true;
            } else {
                $actualElement = $entry[$i];
            }

            if ($elementIsVariable) {
                if (strpos($actualElement, '()')) {
                    $actualElement = substr($actualElement, 0, -2);
                    if (method_exists($this->owner->ClassName, $actualElement)) {
                        $content = $this->owner->{$actualElement}();
                    } else {
                        $content = '';
                    }
                } elseif (strpos($actualElement, '.')) {
                    $HasOneArray = explode(".", $actualElement);
                    $object = $this->owner->{$HasOneArray[0]}();
                    if (isset($object->$HasOneArray[1]) && $object->ID != 0) {
                        $content = $object->$HasOneArray[1];
                    } else {
                        $content = '';
                    }
                } else {
                    $obj = $this->owner->obj($actualElement);
                    $content = $obj->Value;
                }
            } else {
                $content = $actualElement;
            }
            if ($i == 0) {
                $return = $content;
            } else {
                $return .= $content;
            }
        }
        $return = preg_replace('/\s+/', ' ', preg_replace('/<[^>]*>/', ' ', $return));
        if (strlen($return) < 140) {
            return $return;
        } else {
            return preg_replace("/[^ ]*$/", '', substr($return, 0, 140));
        }
    }

    /**
     * BetterMetaDescription returns the current MetaDescription.
     * If there is no MetaDescription then the generated MetaDescription will be used (if existing).
     * If there is even no generated MetaDescription the function will return false
     *
     * @return string       String containg either the MetaDrescription, the genereated MetaDescription or false
     */
    public function BetterMetaDescription()
    {
        if ($this->owner->MetaDescription != '') {
            return $this->owner->MetaDescription;
        } else {
            if (isset($this->current_meta_desc)) {
                return $this->current_meta_desc;
            }
            $classname = $this->owner->ClassName;
            $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
            if (isset($yamlsettings) && isset($yamlsettings['MetaDescription'])) {
                $val = $yamlsettings['MetaDescription'];
                $return = $this->checkBetterMetaDescriptionYaml($val);
                $this->current_meta_desc = $return;
                return $return;
            } else {
                $this->current_meta_desc = $this->owner->GenMetaDesc;
                return $this->owner->GenMetaDesc;
            }
        }
    }

    /**
     * getFBFormFields delivers the localized Data for the FBType-Field in an Array. This data is used in the frontend to generate the og:type value.
     * @return [Array] Array containing the key and localized value pairs for the different FBTypes.
     */
    private function getFBFormFields()
    {
        $FBTypeFields = $this->owner->dbObject('FBType')->enumValues();
        $FBFormArray = array();

        foreach ($FBTypeFields as $FBFieldKey => $FBFieldValue) {
            switch ($FBFieldKey) {
          case 'product':
            $FBFormArray['product'] = _t('SeoHeroTool.FBType_Product', 'Product');
            break;
          case 'article':
            $FBFormArray['article'] = _t('SeoHeroTool.FBType_Article', 'Article or Blogpost');
            break;
          default:
            $FBFormArray['website'] = _t('SeoHeroTool.FBType_Website', 'Website - default value');
          }
        }
        return $FBFormArray;
    }

    /**
     * getSeoFolloFields delivers the localized Data for the Follow Type for robots.
     * @return [Array] Array containing the key and localized value pairs for the different Follow Types.
     */
    private function getSeoFollowFields()
    {
        $SeoFollowFields = $this->owner->dbObject('Follow')->enumValues();
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
        return $SeoFormArray;
    }

    /**
     * getFBTypeFromConfig returns, if existing, the value for FBType (which is used in og:type) which was stored for this
     * classname in the config.yml file.
     *
     * @return string Containing the value for og:type stored in the config.yml
     */
    private function getFBTypeFromConfig()
    {
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
        if ($yamlsettings && isset($yamlsettings['FBType'])) {
            return $yamlsettings['FBType'];
        }
    }

    /*
      public function which gets called for example in the template to get the value for og:type
      Uses private function to check if there is a configuration which needs to be read
     */
    /**
     * checkFBType checks and returns the value which is used in og:type. This value can either be selected via the backend or
     * can be generated via the config.yml.
     * If there is a value from the configuration and this value gets not overturned for the actual page, then the configuration value will be used.
     *
     * @return string Containg the og:type value, which can be one of the enumValues from FBType (see above)
     */
    public function checkFBType()
    {
        $check = $this->getFBTypeFromConfig();
        if (!$this->owner->FBTypeOverride && isset($check)) {
            return $check;
        } else {
            return $this->owner->FBType;
        }
    }

    /**
     * onBeforeWrite checks before the dataobject gets written. If the MetaDescription is empty, then it will generate a Description from the Content.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($this->owner->BetterSiteTitle == '') {
            $this->owner->BetterSiteTitle = null;
        }

        if ($this->owner->MetaDescription == '') {
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

    /**
     * SMPreviewImage takes care for checking if an Image for Facebook and Twitter is defined. First check is if there is one uploaded in the backend,
     * if not it will be checked if there is one set via the configuration.
     * @param [type] $type Either FB for Facebook or TW for Twitter, otherwise false will be returned
     * @return [type]      returns either false or the absolute path to the defined image
     */
    public function SMPreviewImage($type)
    {
        //  debug::show($type);
        if ($type == 'FB') {
            if ($this->owner->FBImage()->exists()) {
                if ($this->owner->FBImage()->exists()) {
                    if (class_exists('FocusPointImage')) {
                        return $this->owner->FBImage()->CroppedFocusedImage(1200, 627)->AbsoluteURL;
                    } else {
                        return $this->owner->FBImage()->CroppedImage(1200, 627)->AbsoluteURL;
                    }
                }
            }
        } elseif ($type == 'TW') {
            if ($this->owner->TWImage()->exists()) {
                if (class_exists('FocusPointImage')) {
                    return $this->owner->TWImage()->CroppedFocusedImage(600, 314)->AbsoluteURL;
                } else {
                    return $this->owner->TWImage()->CroppedImage(600, 314)->AbsoluteURL;
                }
            }
        } else {
            return false;
        }


        // Check for YAML Configuration
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', $classname);
        if ($yamlsettings) {
            if (isset($yamlsettings[$type.'Image'])) {
                //debug::show('hier');

                $return = $this->checkSMImageYAMLSettings($yamlsettings, $type.'Image');
                return $return;
            }
        } else {
            return false;
        }
    }

    /**
     * checkSMImageYAMLSettings checks if an Image for twitter or facebook is set correctly if configured via configuration file
     * @param  [type] $entry  the actual yaml settings
     * @param  [type] $SMType either FBImage for facebook or TWImage for Twitter
     * @return [type]         returns the value of this. Right now this has to be an absolute path, but this will not be checked
     */
    public function checkSMImageYAMLSettings($entry, $SMType)
    {
        $return = false;
        if (isset($entry) && isset($entry[$SMType])) {
            $fbimageList = $entry[$SMType];
            for ($i = 0; $i < count($fbimageList); $i++) {
                $elementIsVariable = false;
                if (substr($fbimageList[$i], 0, 1) == '$') {
                    $actualElement = substr($fbimageList, 1);
                    $elementIsVariable = true;
                } else {
                    $actualElement = $fbimageList[$i];
                }
                if ($elementIsVariable) {
                    #Variable
                    if (strpos($actualElement, '()')) {
                        #function
                        $actualElement = substr($actualElement, 0, -2);
                        if (method_exists($this->owner->ClassName, $actualElement)) {
                            $return = $this->owner->{$actualElement}();
                        }
                    }
                }
            }
        }
        return $return;
    }
}

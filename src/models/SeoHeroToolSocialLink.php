<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;

class SeoHeroToolSocialLink extends DataObject
{
    private static $db = array(
    'Name' => 'Text',
    'Link' => 'Text',
    'UserName' => 'Text',
    'IconName' => 'Text',
    'DisplayInSocialLoop' => 'Boolean',
    'SortOrder' => 'Int'
  );

    private static $table_name = 'SeoHeroToolSocialLink';
    private static $default_sort = 'SortOrder ASC';

    private static $has_one = array(
    'SeoHeroToolSchemaCompany' => 'SeoHeroToolSchemaCompany'
  );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SeoHeroToolSchemaCompany');
        $DropDownValues = $fields->addFieldToTab('Root.Main', new LiteralField('', _t('SeoHeroToolSocialLink.ChooseFromSelectionOrEnterData', 'Please choose either an entry from the Dropdown or enter a Value on your own.')));
        $fields->addFieldToTab('Root.Main', new DropdownField('NameFromSelection', _t('SeoHeroToolSocialLink.NameFromSelection', 'Name from Selection'), array(''=>'No Selection', 'Facebook'=>'Facebook', 'Twitter'=>'Twitter', 'Instagram'=>'Instagram', 'LinkedIn'=>'LinkedIn', 'YouTube'=>'YouTube', 'Google+'=>'Google+', 'Xing' => 'Xing')));
        $fields->addFieldToTab('Root.Main', new TextField('Name', _t('SeoHeroToolSocialLink.Name', 'Name')));
        $fields->addFieldToTab('Root.Main', new TextField('Link', _t('SeoHeroToolSocialLink.Link', 'Link (inkl. http://)')));
        $fields->addFieldToTab('Root.Main', new TextField('UserName', _t('SeoHeroToolSocialLink.UserName', 'Username for example Twitter Username')));
        $fields->addFieldToTab('Root.Main', new TextField('IconName', _t('SeoHeroToolSocialLink.IconName', 'Icon Name')));
        $fields->addFieldToTab('Root.Main', new CheckboxField('DisplayInSocialLoop', _t('SeoHeroToolSocialLink.DisplayInSocialLoop', 'Make available in Social Loop Function')));
        $fields->addFieldToTab('Root.Main', new LiteralField('SocialLoopStatement', _t('SeoHeroToolSocialLink.SocialLoopStatement', 'The Social Loop can be sorted either alphabetical or it can be sorted via the overview')));
        $fields->addFieldToTab('Root.Main', new TextField('SortOrder', _t('SeoHeroToolSocialLink.SortOrder', 'Sorting in Social Loop')));
        $fields->addFieldToTab('Root.Main', new LiteralField('SortOrderStatement', _t('SeoHeroToolSocialLink.SortOrderStatement', 'Sorting in Social Loop is by default by SortOrder Ascending')));
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if ($this->Name == null) {
            $this->Name = $this->NameFromSelection;
        }
    }
}

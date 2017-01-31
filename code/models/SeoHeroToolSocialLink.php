<?php
class SeoHeroToolSocialLink extends DataObject
{
  private static $db = array(
    'Name' => 'Text',
    'Link' => 'Text',
    'UserName' => 'Text',
    'IconName' => 'Text',
    'DisplayInSocialLoop' => 'Boolean'
  );

  private static $has_one = array(
    'SeoHeroToolSchemaCompany' => 'SeoHeroToolSchemaCompany'
  );

  public function getCMSFields(){
      $fields = parent::getCMSFields();
      $DropDownValues =
      $fields->addFieldToTab('Root.Main', new LiteralField('', _t('SeoHeroToolSocialLink.ChooseFromSelectionOrEnterData', 'Please choose either an entry from the Dropdown or enter a Value on your own.')));
      $fields->addFieldToTab('Root.Main', new DropdownField('NameFromSelection', _t('SeoHeroToolSocialLink.NameFromSelection', 'Name from Selection'), array(''=>'Keine Auswahl', 'Facebook'=>'Facebook', 'Twitter'=>'Twitter', 'Instagram'=>'Instagram', 'LinkedIn'=>'LinkedIn', 'YouTube'=>'YouTube', 'Google+'=>'Google+')));
      $fields->addFieldToTab('Root.Main', new TextField('Name', _t('SeoHeroToolSocialLink.Name', 'Name')));
      $fields->addFieldToTab('Root.Main', new TextField('Link', _t('SeoHeroToolSocialLink.Link', 'Link (inkl. http://)')));
      $fields->addFieldToTab('Root.Main', new TextField('UserName', _t('SeoHeroToolSocialLink.UserName', 'Username for example Twitter Username')));
      $fields->addFieldToTab('Root.Main', new TextField('IconName', _t('SeoHeroToolSocialLink.IconName', 'Icon Name')));
      $fields->addFieldToTab('Root.Main', new CheckboxField('DisplayInSocialLoop', _t('SeoHeroToolSocialLink.DisplayInSocialLoop', 'Make available in Social Loop Function?')));
      return $fields;
  }

  public function onBeforeWrite(){
    parent::onBeforeWrite();

    if($this->Name == NULL){
      $this->Name = $this->NameFromSelection;
    }

  }

}

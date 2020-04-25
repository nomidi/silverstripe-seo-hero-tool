<?php

    namespace nomidi\SeoHeroTool;


    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DateField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TimeField;
    use SilverStripe\ORM\DataObject;
    use nomidi\SeoHeroTool\SeoHeroToolOpeningHour;

    class SeoHeroToolOpeningHourEntry extends DataObject
    {
        private static $singular_name = 'Uhrzeiten';

        private static $table_name = 'SeoHeroToolOpeningHourEntry';

        private static $db = [
            'OpeningTime' => 'Time',
            'ClosingTime' => 'Time',
            'OnMonday'=>'Boolean',
            'OnTuesday'=>'Boolean',
            'OnWednesday'=>'Boolean',
            'OnThursday'=>'Boolean',
            'OnFriday'=>'Boolean',
            'OnSaturday'=>'Boolean',
            'OnSunday'=>'Boolean'
        ];

        private static $has_one = [
            'SeoHeroToolOpeningHour'=>SeoHeroToolOpeningHour::class
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->removeByName('SeoHeroToolSchemaCompanyID');

            $fields->addFieldToTab('Root.Main', new TimeField('OpeningTime', _t('SeoHeroToolSchemaCompany.OpeningTime', 'Opening Hour')));
            $fields->addFieldToTab('Root.Main', new TimeField('ClosingTime', _t('SeoHeroToolSchemaCompany.ClosingTime', 'Closing Hour')));

            $fields->addFieldToTab('Root.Main', new CheckboxField('OnMonday','Montag'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnTuesday','Dienstag'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnWednesday','Mittwoch'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnThursday','Donnerstag'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnFriday','Freitag'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnSaturday','Samstag'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('OnSunday','Sonntag'));


            return $fields;
        }
    }
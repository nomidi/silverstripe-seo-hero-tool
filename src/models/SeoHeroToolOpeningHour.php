<?php

    namespace nomidi\SeoHeroTool;


use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TimeField;
use SilverStripe\ORM\DataObject;
    use nomidi\SeoHeroTool\SeoHeroToolSchemaCompany;

    class SeoHeroToolOpeningHour extends DataObject
    {
        private static $singular_name = 'Öffnungszeiten';

        private static $table_name = 'SeoHeroToolOpeningHour';

        private static $db = [
            'OpeningHoursTitle'=>'Varchar(100)',
            'OpeningHoursStart'=>'Date',
            'OpeningHoursEnd'=>'Date',

        ];

        private static $has_one = [
            'SeoHeroToolSchemaCompany'=>SeoHeroToolSchemaCompany::class
        ];

        private static $has_many = [
            'SeoHeroToolOpeningHourEntries'=>SeoHeroToolOpeningHourEntry::class
        ];


        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->removeByName('SeoHeroToolSchemaCompanyID');
            $fields->addFieldToTab('Root.Main', new TextField('OpeningHoursTitle', _t('SeoHeroToolSchemaCompany.OpeningHoursTitle', 'Title (internal)')));

            $fields->addFieldToTab('Root.Main', new DateField('OpeningHoursStart', _t('SeoHeroToolSchemaCompany.OpeningHoursStart', 'Date Start (optional)')));
            $fields->addFieldToTab('Root.Main', new DateField('OpeningHoursEnd', _t('SeoHeroToolSchemaCompany.OpeningHoursEnd', 'Date End (optional)')));

            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursMondayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursMondayOpeningTime', 'Opening Hour Monday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursMondayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursMondayClosingTime', 'Closing Hour Monday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursTuesdayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursTuesdayOpeningTime', 'Opening Hour Tuesday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursTuesdayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursTuesdayClosingTime', 'Closing Hour Tuesday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursWednesdayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursWednesdayOpeningTime', 'Opening Hour Wednesday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursWednesdayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursWednesdayClosingTime', 'Closing Hour Wednesday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursThursdayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursThursdayOpeningTime', 'Opening Hour Thursday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursThursdayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursThursdayClosingTime', 'Closing Hour Thursday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursFridayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursFridayOpeningTime', 'Opening Hour Friday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursFridayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursFridayClosingTime', 'Closing Hour Friday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursSaturdayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursSaturdayOpeningTime', 'Opening Hour Saturday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursSaturdayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursSaturdayClosingTime', 'Closing Hour Saturday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursSundayOpeningTime', _t('SeoHeroToolSchemaCompany.OpeningHoursSundayOpeningTime', 'Opening Hour Sunday')));
            $fields->addFieldToTab('Root.Main', new TimeField('OpeningHoursSundayClosingTime', _t('SeoHeroToolSchemaCompany.OpeningHoursSundayClosingTime', 'Closing Hour Sunday')));

            return $fields;
        }

        private static $default_sort = 'OpeningHoursStart ASC';

        private static $summary_fields = array(
            'OpeningHoursTitle' => 'Title',
            'OpeningHoursStart'=> 'Start',
            'OpeningHoursEnd'=>'Ende'
        );


    }

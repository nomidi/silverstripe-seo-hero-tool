<?php

namespace nomidi\SeoHeroTool;

use Dynamic\CountryDropdownField\Fields\CountryDropdownField;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;

use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\DB;
use SilverStripe\Security\Permission;

class SeoHeroToolSchemaCompany extends DataObject
{
    private static $table_name = 'SeoHeroToolSchemaCompany';
    private static $db = array(
    'Company' => 'Text',
    #'CompanyMore' =>  'Text',
    'Country' =>  'Text',
    'OrganizationType' => 'Text',
    'Street' => 'Text',
    'HouseNmbr' => 'Varchar(10)',
    'Postal' => 'Text',
    'Location' => 'Text',
    'Tel' => 'Text',
    'Mail' => 'Text',
    'Link' => 'Text',
    'VatID' => 'Text',
    'Latitude' => 'Float()',
    'Longitude' => 'Float()',

    'OpeningHoursInSchema' => 'Boolean'

  );

    private static $has_one = array(
    'Logo' => Image::class);

    /*private static $has_many = array(
      'SeoHeroToolSocialLinks' => 'SeoHeroToolSocialLink'
    );*/


    private static $has_many = [
        'SeoHeroToolOpeningHours'=>SeoHeroToolOpeningHour::class
    ];

    private static $singular_name = 'Schema.org Company';


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SeoHeroToolSocialLinks');
        $fields->addFieldToTab('Root.Main',
          SchemaOrganizationType::create('OrganizationType', _t('SeoHeroToolSchemaCompany.OrganizationType', 'Organization Type')));
        $fields->addFieldToTab('Root.Main', new TextField('Company', _t('SeoHeroToolSchemaCompany.Company', 'Company')));
        #$fields->addFieldToTab('Root.Main', new TextField('CompanyMore', _t('SeoHeroToolSchemaCompany.CompanyMore', 'Company second row')));
        $fields->addFieldToTab('Root.Main', new TextField('Street', _t('SeoHeroToolSchemaCompany.Street', 'Street')));
        $fields->addFieldToTab('Root.Main', new TextField('HouseNmbr', _t('SeoHeroToolSchemaCompany.HouseNmbr', 'Housenumber')));
        $fields->addFieldToTab('Root.Main', new TextField('Postal', _t('SeoHeroToolSchemaCompany.Postal', 'Postal')));
        $fields->addFieldToTab('Root.Main', new TextField('Location', _t('SeoHeroToolSchemaCompany.Location', 'Location')));
        $fields->addFieldToTab('Root.Main', new CountryDropdownField('Country', _t('SeoHeroToolSchemaCompany.Country', 'Country')));
        $fields->addFieldToTab('Root.Main', new TextField('Tel', _t('SeoHeroToolSchemaCompany.Tel', 'Telephon')));
        $fields->addFieldToTab('Root.Main', new TextField('Mail', _t('SeoHeroToolSchemaCompany.Mail', 'Mail')));
        $fields->addFieldToTab('Root.Main', new TextField('Link', _t('SeoHeroToolSchemaCompany.Link', 'Website')));
        $fields->addFieldToTab('Root.Main', new TextField('VatID', _t('SeoHeroToolSchemaCompany.VatID', 'VatID')));
        $fields->addFieldToTab('Root.OpeningHours', new Literalfield('', _t('SeoHeroToolSchemaCompany.OpeningHoursText', "Hours should be in the format 0900 (for 09.00) or 1730 (for 17.30)")));
        $fields->addFieldToTab('Root.OpeningHours', new CheckboxField('OpeningHoursInSchema', _t('SeoHeroToolSchemaCompany.OpeningHoursInSchema', 'Display Opening Hours in Schemadata?')));

        $logoField = new UploadField(
                $name = 'Logo',
                $title = _t('SeoHeroToolSchemaCompany.Logo', 'Company Logo')
            );
        $logoField->setFolderName('seo-hero-tool');
        $sizeMB = 2;
        $size = $sizeMB * 1024 * 1024; // 2 MB in bytes
        $logoField->getValidator()->setAllowedMaxFileSize($size);
        $logoField->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
        $fields->addFieldToTab('Root.Main', $logoField);
        $fields->addFieldToTab('Root.Main', $latf = new LatLngField('Latitude'));
        $latf->setConfig('lat', true);
        $fields->addFieldToTab('Root.Main', $lngf = new LatLngField('Longitude'));
        $lngf->setConfig('lng', true);

        $fields->removeByName('SeoHeroToolOpeningHours');
         $openinggrid = new GridField("SeoHeroToolOpeningHours", "Öffnungszeiten", $this->SeoHeroToolOpeningHours(), GridFieldConfig_RelationEditor::create(), $this);
         $config = $openinggrid->getConfig();
         $fields->addFieldToTab('Root.OpeningHours', $openinggrid);


        /*$grid = new GridField("SeoHeroToolSocialLinks", "Social Links", $this->SeoHeroToolSocialLinks(), GridFieldConfig_RelationEditor::create(), $this);
        $config = $grid->getConfig();
        $config->getComponentByType('GridFieldPaginator')->setItemsPerPage(20);
        $fields->addFieldToTab('Root.SocialLinks', $grid);*/

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (($this->Latitude == 0 || $this->Longitude == 0) && ($this->Street != "" && $this->HouseNmbr != "" && $this->Postal != "" && $this->Location != "" && $this->Country != "")) {
            $address = urlencode($this->Street." ".$this->HouseNmbr." ".$this->PostalCode." ".$this->Location);
            $region = $this->Country;

            $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
            $json = json_decode($json);
            if (count($json->{'results'}) >= 1) {
                $this->Latitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $this->Longitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
            }
        }
    }

    public static function OpeningHours()
    {
        $Data = SeoHeroToolSchemaCompany::get()->First();
        $ArrayList = new ArrayList();
        if ($Data->OpeningHoursMondayOpeningTime != null || $Data->OpeningHoursMondayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Monday', 'Open' => $Data->OpeningHoursMondayOpeningTime, 'Close'=>$Data->OpeningHoursMondayClosingTime)));
        }
        if ($Data->OpeningHoursTuesdayOpeningTime != null || $Data->OpeningHoursTuesdayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Tuesday', 'Open' => $Data->OpeningHoursTuesdayOpeningTime, 'Close'=>$Data->OpeningHoursTuesdayClosingTime)));
        }
        if ($Data->OpeningHoursWednesdayOpeningTime != null || $Data->OpeningHoursWednesdayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Wednesday', 'Open' => $Data->OpeningHoursWednesdayOpeningTime, 'Close'=>$Data->OpeningHoursWednesdayClosingTime)));
        }
        if ($Data->OpeningHoursThursdayOpeningTime != null || $Data->OpeningHoursThursdayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Thursday', 'Open' => $Data->OpeningHoursThursdayOpeningTime, 'Close'=>$Data->OpeningHoursThursdayClosingTime)));
        }
        if ($Data->OpeningHoursFridayOpeningTime != null || $Data->OpeningHoursFridayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Friday', 'Open' => $Data->OpeningHoursFridayOpeningTime, 'Close'=>$Data->OpeningHoursFridayClosingTime)));
        }
        if ($Data->OpeningHoursSaturdayOpeningTime != null || $Data->OpeningHoursSaturdayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Saturday', 'Open' => $Data->OpeningHoursSaturdayOpeningTime, 'Close'=>$Data->OpeningHoursSaturdayClosingTime)));
        }
        if ($Data->OpeningHoursSundayOpeningTime != null || $Data->OpeningHoursSundayClosingTime != null) {
            $ArrayList->push(new ArrayData(array('Day'=>'Sunday', 'Open' => $Data->OpeningHoursSundayOpeningTime, 'Close'=>$Data->OpeningHoursSundayClosingTime)));
        }
        return $ArrayList;
    }


    public static function current_entry()
    {
        if ($entry = DataObject::get_one(SeoHeroToolSchemaCompany::class)) {
            return $entry;
        }
        return self::make_site_config();
    }
    /**
     * Create SiteConfig with defaults from language file.
     *
     * @return SiteConfig
     */
    public static function make_entry()
    {
        $config = SeoHeroToolSchemaCompany::create();
        $config->write();
        return $config;
    }

  /**
     * Setup a default SiteConfig record if none exists
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $entry = DataObject::get_one(SeoHeroToolSchemaCompany::class);
        if (!$entry) {
            self::make_entry();
            DB::alteration_message("Added default SeoHeroToolSchemaCompany", "created");
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

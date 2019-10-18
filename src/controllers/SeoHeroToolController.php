<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\ORM\DataExtension;
use SilverStripe\View\Requirements;
use nomidi\SeoHeroTool\SeoHeroToolGoogleAnalytic;
use SilverStripe\Core\Config\Config;
use nomidi\SeoHeroTool\SeoHeroToolSchemaCompany;
use nomidi\SeoHeroTool\SeoHeroToolSocialLink;
use SilverStripe\Control\Director;




/**
 * SeoHeroToolController takes care that all SeoHeroTool modules will be included.
 */
class SeoHeroToolController extends DataExtension
{
    public function onAfterInit()
    {
        $class = get_class($this->owner);
        $yamlsettings = config::inst()->get('SeoHeroToolDataObject', 'exclude');
        if ($this->owner->data() && $class != 'ErrorPage_Controller' && !in_array($class,$yamlsettings)) {
            # fix installation issue with non existing pages
          # todo find better solution during installation
          Requirements::insertHeadTags($this->SeoHeroToolMeta());
        }
    }

    /**
     * SeoHeroToolMeta runs all necessary Seo Hero Tool Parts.
     */
    public function SeoHeroToolMeta()
    {
        return $this->compressTemplate(
          '<!-- Seo Hero Tools for Silverstripe -->' .
          $this->getCanonicalURL() .
          $this->getMetaRobots().
          $this->getGoogleAnalytic().
          $this->getSchemaCompany().
          $this->getMetaSocialMedia() .
          $this->getTranslationLink() .
          $this->getSchemaDataObject() .
          '<!-- Seo Hero Tools for Silverstripe -->'
        );
    }

    public function getCanonicalURL()
    {
        $template = $this->owner->renderWith('nomidi\SeoHeroTool\SeoHeroToolCanonical');
        return $this->compressTemplate($template);
    }

    public function getGoogleAnalytic()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false &&
            strpos($_SERVER['REQUEST_URI'], '/install') === false) {
            $AnalyticsData = SeoHeroToolGoogleAnalytic::get()->first();
            if ($AnalyticsData->AnalyticsKey == '') {
                return false;
            }
            $template = $this->owner->customise(array(
                'GoogleAnalytics' => $AnalyticsData,
            ))->renderWith('nomidi\SeoHeroTool\SeoHeroToolGoogleAnalytic');
          //  debug::show($template);
          $env_type =  Config::inst()->get('Director', 'environment_type');
            if ($AnalyticsData->ActivateInMode === $env_type || $AnalyticsData->ActivateInMode === 'All') {
                return $template;
            }
            return false;
        } else {
            return false;
        }
    }

    /*
    *   getSchemaCompany() returns the Company Schema in a Schema.org format.
     */
    public function getSchemaCompany()
    {
        $SchemaCompany = SeoHeroToolSchemaCompany::get()->first();
        /*$value = $SchemaCompany->databaseFields(SeoHeroToolSchemaCompany::class);
        foreach ($value as $k => $v) {
            if ($k != 'LogoID') {
                $SchemaCompany->$k = stripslashes($SchemaCompany->$k);
            }
        }
        if ($SchemaCompany->OrganizationType != "") {
            $template = $this->owner->customise(array('SchemaCompany'=>$SchemaCompany))->renderWith('nomidi\SeoHeroTool\SeoHeroToolSchemaCompany');
            return $template;
        }*/
    }

    /*
    *   getMetaRobots() returns the Robots Information for the actual Website.
     */
    public function getMetaRobots()
    {
        $template = $this->owner->renderWith('nomidi\SeoHeroTool\SeoHeroToolMetaRobots');
        return $this->compressTemplate($template);
    }


    /**
     *
    */
   public function getSchemaDataObject()
   {
       $template = $this->owner->renderWith('nomidi\SeoHeroTool\SeoHeroToolSchemaDataObject');
       return $this->compressTemplate($template);
   }
    /*
    *   getMetaSocialMedia() returns the site information prepared for facebook, twitter etc. for better sharing
     */
     public function getMetaSocialMedia()
     {
         $template = $this->owner->customise(array(
            'SeoHeroToolSocialMediaChannels' => SeoHeroToolSocialLink::get(),
        ))->renderWith('nomidi\SeoHeroTool\SeoHeroToolMetaSocialMedia');
         return $this->compressTemplate($template);
     }

    public function getTranslationLink()
    {
        $template = $this->owner->renderWith('nomidi\SeoHeroTool\SeoHeroToolTranslationLink');
        return $this->compressTemplate($template);
    }

    /**
     * compressTemplate compresses the template data and minimize them
     * @param  string $template uncompressed Meta Information
     * @return string $template compressed Meta Information
     */
    private function compressTemplate($template)
    {
        if (Director::isLive()) {
            return trim(str_replace(array("\r\n", "\r", "\n"), "", $template));
        } else {
            return $template;
        }
    }

    /**
     * getSocialLoop returns all Social Media Channel which are allowed to be displayed in a social loop.
     * By default this data is sorted by SortOrder. But the loop can in any template be sorted by the default
     * Silverstripe Sorting functions
     */
    public function getSocialLoop()
    {
        $data = SeoHeroToolSocialLink::get()->Filter(array('DisplayInSocialLoop' => 1));
        return $data;
    }
}

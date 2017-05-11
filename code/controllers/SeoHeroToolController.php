<?php

class SeoHeroToolController extends DataExtension
{
    public function onAfterInit()
    {
        Requirements::insertHeadTags($this->SeoHeroToolMeta());
    }


    public function SeoHeroToolMeta()
    {
        return $this->compressTemplate(
          '<!-- Seo Hero Tools for Silverstripe -->' .
          $this->getMetaRobots().
          $this->getGoogleAnalytic().
          $this->getSchemaCompany().
          $this->getMetaSocialMedia() .
          '<!-- Seo Hero Tools for Silverstripe -->'
        );
    }

    public function getGoogleAnalytic()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false) {
            $AnalyticsData = SeoHeroToolGoogleAnalytic::get()->first();
            //debug::show($AnalyticsData);
            $template = $this->owner->customise(array(
                'GoogleAnalytics' => $AnalyticsData,
            ))->renderWith('SeoHeroToolGoogleAnalytic');
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
        $value = $SchemaCompany->custom_database_fields('SeoHeroToolSchemaCompany');
        foreach ($value as $k => $v) {
            if ($k != 'LogoID') {
                $SchemaCompany->$k = stripslashes($SchemaCompany->$k);
            }
        }
        if ($SchemaCompany->OrganizationType != "") {
            $template = $this->owner->customise(array('SchemaCompany'=>$SchemaCompany))->renderWith('SeoHeroToolSchemaCompany');
            return $template;
        }
    }

    /*
    *   getMetaRobots() returns the Robots Information for the actual Website.
     */
    public function getMetaRobots()
    {
        $template = $this->owner->renderWith('SeoHeroToolMetaRobots');
        return $this->compressTemplate($template);
    }

    /*
    *   getMetaSocialMedia() returns the site information prepared for facebook, twitter etc. for better sharing
     */
     public function getMetaSocialMedia()
     {
         $template = $this->owner->customise(array(
            'SeoHeroToolSocialMediaChannels' => SeoHeroToolSocialLink::get(),
        ))->renderWith('SeoHeroToolMetaSocialMedia');
         return $this->compressTemplate($template);
     }

    /**
     * compressTemplate
     * @param  string $template uncompressed Meta Information
     * @return string $template compressed Meta Information
     */
    private function compressTemplate($template)
    {
        if (Director::isLive()) {
            return trim(preg_replace('/[^[:print:]]\s+/', "", $template));
        } else {
            return $template;
        }
    }

    public function getSocialLoop()
    {
        $data = SeoHeroToolSocialLink::get()->Filter(array('DisplayInSocialLoop' => 1));
        return $data;
    }
}

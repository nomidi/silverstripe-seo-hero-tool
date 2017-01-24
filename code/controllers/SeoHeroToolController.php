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
          $this->getGoogleAnalytics().
          $this->getSchemaCompany().
          '<!-- Seo Hero Tools for Silverstripe -->'
        );
    }

    public function getGoogleAnalytics()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false) {
            $AnalyticsData = SeoHeroToolGoogleAnalytics::get()->first();
            //debug::show($AnalyticsData);
            $template = $this->owner->customise(array(
                'GoogleAnalytics' => $AnalyticsData,
            ))->renderWith('SeoHeroToolGoogleAnalytics');
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


    public function getSchemaCompany()
    {
        $SchemaCompany = SeoHeroToolSchemaCompany::get()->first();
        $value = $SchemaCompany->custom_database_fields('SeoHeroToolSchemaCompany');
        foreach ($value as $k => $v){
          if($k != 'LogoID'){
            $SchemaCompany->$k = stripslashes($SchemaCompany->$k);
          }
        }
        //debug::show($SchemaCompany);
        if ($SchemaCompany->OrganizationType != "") {
            $template = $this->owner->customise(array('SchemaCompany'=>$SchemaCompany))->renderWith('SeoHeroToolSchemaCompany');
            return $template;
        }
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
}

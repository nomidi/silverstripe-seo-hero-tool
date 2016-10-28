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
          $this->getGoogleAnalytics()
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

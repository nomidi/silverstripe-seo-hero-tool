<?php

class SeoHeroToolController extends Extension
{
    public function SeoHeroToolMeta()
    {
        return $this->compressTemplate(
          $this->getGoogleAnalytics()
        );
    }

    public function getGoogleAnalytics()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false &&
            strpos($_SERVER['REQUEST_URI'], '/dev') === false) {
            $AnalyticsData = SeoHeroToolGoogleAnalytics::get()->first();
          //  debug::show($AnalyticsData);
            $template = $this->owner->customise(array(
                'GoogleAnalytics' => $AnalyticsData,
            ))->renderWith('SeoHeroToolGoogleAnalytics');
          //  debug::show($template);
            return $template;
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

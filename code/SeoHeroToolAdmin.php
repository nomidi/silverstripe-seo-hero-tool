<?php

class SeoHeroToolAdmin extends ModelAdmin
{
    private static $url_segment = 'seo-hero-tool-admin';

    private static $managed_models = array(
        'SeoHeroToolGoogleAnalytics'
    );

    public function init()
    {
        parent::init();
    }
}

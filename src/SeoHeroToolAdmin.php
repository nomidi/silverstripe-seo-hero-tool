<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\Admin\ModelAdmin;


class SeoHeroToolAdmin extends ModelAdmin
{
    private static $url_segment = 'seo-hero-tool-admin';

    private static $menu_title = 'SeoHeroTool';

    private static $managed_models = array(
        'nomidi\SeoHeroTool\SeoHeroToolGoogleAnalytic','nomidi\SeoHeroTool\SeoHeroToolSchemaCompany','nomidi\SeoHeroTool\SeoHeroToolEditFile'
    );

    public function init()
    {
        parent::init();
    }
}

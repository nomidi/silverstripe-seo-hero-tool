<?php

class SeoHeroToolAdmin extends ModelAdmin
{
    private static $url_segment = 'seo-hero-tool-admin';

    private static $managed_models = array(
        'SeoHeroToolGoogleAnalytic','SeoHeroToolSchemaCompany','SeoHeroToolEditFile'
    );

    public function init()
    {
        parent::init();
    }


/*

    public function getEditForm($id = null, $fields = null){
      $SocialLink = SeoHeroToolSocialLink::get();
      $SocialLinksEditLink = Controller::join_links($this->Link($this->sanitiseClassName($this->modelClass)),'EditForm/field/SeoHeroToolGoogleAnalytic/item/1/edit');

      $this->Overview = $this->owner->customise(array(
          'gakey' =>'1',
          'SocialLinks' => $SocialLink,
          'SocialLinksEditLink' => $SocialLinksEditLink,
      ))->renderWith('SeoHeroToolAdminOverview');
      return $this->renderWith('SeoHeroToolConfigEditForm');
    }
*/
}

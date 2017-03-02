<?php
class SeoHeroToolLeftAndMain extends LeftAndMain{
  private static $url_segment = 'testerei';
  private static $url_rule = '$Action/$ID/$OtherID';
  private static $menu_title = 'Mein Test';
  private static $tree_class = array('SeoHeroToolGoogleAnalytic','SeoHeroToolSchemaCompany');

  private static $allowed_actions = array('SocialLink', 'SeoHeroToolGoogleAnalytic');

  public function getEditForm($id = null, $fields = null){
    $SocialLink = SeoHeroToolSocialLink::get();
    $SocialLinksEditLink = Controller::join_links($this->Link($this->modelClass), 'SocialLink/1');
    //$GoogleAnalyitcEditLink = 'admin/testerei/SeoHeroToolGoogleAnalytic/EditForm/field/SeoHeroToolGoogleAnalytic/item/1/edit';
    $GoogleAnalyitcEditLink = 'admin/seo-hero-tool-admin/SeoHeroToolGoogleAnalytic/EditForm/field/SeoHeroToolGoogleAnalytic/item/1/edit';
    //$SocialLinksEditLink = 'test';asdas1d
    $this->Overview = $this->owner->customise(array(
        'gakey' =>'1',
        'GoogleAnalyitcEditLink' => $GoogleAnalyitcEditLink,
        'SocialLinks' => $SocialLink,
        'SocialLinksEditLink' => $SocialLinksEditLink,
    ))->renderWith('SeoHeroToolAdminOverview');
    return $this->renderWith('SeoHeroToolConfigEditForm');
  }

  public function SocialLink(){
    echo "hier sind wir";
  }

  public function SeoHeroToolGoogleAnalytic(){
    $ga = new SeoHeroToolGoogleAnalytic();
    //debug::show($ga);
    //die();
  //  return $ga->getCMSFields();
    return $ga;
  }

}

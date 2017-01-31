<?php
class SeoHeroToolSchemaCompanyTest extends FunctionalTest
{
    protected static $fixture_file = 'SeoHeroToolControllerTest.yml';
    public static $use_draft_site = true;






    public function testOrganizationType()
    {
        $needle = "@type";
        $ga = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);

        $this->assertTrue(is_numeric($body), _t('SeoHeroToolSchemaCompany.FindOrganizationTypeInTemplate'));

        $ga->OrganizationType = "";
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertFalse(is_numeric($body), _t('SeoHeroToolSchemaCompany.MissingOrganizationTypeInTemplate'));
    }

    public function testJSONisCorrect()
    {
        $needle = '<script type="application/ld+json">';
        $needleEnd = '</script>';
        $ga = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $jsonstart = strpos($response->getBody(), $needle);
        $jsonObject = substr($response->getBody(), $jsonstart+35);
        $jsonend = strpos($jsonObject, $needleEnd);
        $jsonObject = substr($jsonObject, 0, $jsonend);
        $checkJson = json_decode($jsonObject);
      //debug::show($jsonObject);
      $this->assertTrue($checkJson != null);
    }

    public function testJSONwithWrongData()
    {
        $needle = '<script type="application/ld+json">';
        $needleEnd = '</script>';
        $ga = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');
        $ga->Mail = 'info@\"ex"ample.com';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $jsonstart = strpos($response->getBody(), $needle);
        $jsonObject = substr($response->getBody(), $jsonstart+35);
        $jsonend = strpos($jsonObject, $needleEnd);
        $jsonObject = substr($jsonObject, 0, $jsonend);
        $checkJson = json_decode($jsonObject);
        $this->assertTrue($checkJson != null);
    }

    public function testSchemaCompanyFields()
    {
        $data = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');

        $values = $data->custom_database_fields('SeoHeroToolSchemaCompany');
        foreach ($values as $k=>$v) {
            $response = $this->get($this->objFromFixture('Page', 'home')->Link());
            if (isset($datas->{$k})) {
                $body = strpos($response->getBody(), $datas->{$k});
                $this->assertTrue(is_numeric($body), 'Key '.$k.'  with value '.$v.' not found');
            }
        }
    }

    public function testSocialSchemaLinks(){
        $facebookneedle = 'https://www.facebook.com/examplesite';
        $twitterneedle = 'https://www.twitter.com/mysite';
        $instagramneedle = 'https://www.instagram.com/examplesite';
        $data = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');

        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $fbsearch = strpos($response->getBody(), $facebookneedle);
        $this->assertTrue(is_numeric($fbsearch), _t('SeoHeroToolSchemaCompany.DidNotFindFacebookSocialMeta'));

        $twittersearch = strpos($response->getBody(), $twitterneedle);
        $this->assertTrue(is_numeric($twittersearch), _t('SeoHeroToolSchemaCompany.DidNotFindTwitterkSocialMeta'));

        $instasearch = strpos($response->getBody(), $instagramneedle);
        $this->assertTrue(is_numeric($instasearch), _t('SeoHeroToolSchemaCompany.DidNotFindInstagramSocialMeta'));
    }

    public function testgetSocialLoop(){
        $seocontroller = new SeoHeroToolController();
        $data = $seocontroller->getSocialLoop();
        $contentarray = array();
        foreach ($data as $key => $val ){
          $contentarray[] = $val->Name;
        }
        $fbneedle = 'Facebook';
        $twitterneedle = 'Twitter';
        $instagramneedle = 'Instagram';

        $searchfb = in_array($fbneedle, $contentarray);
        $this->assertTrue($searchfb, _t('SeoHeroToolSchemaCompany.DidNotFindFacebookInSocialLoop'));

        $searchtw = in_array($twitterneedle, $contentarray);
        $this->assertTrue($searchtw, _t('SeoHeroToolSchemaCompany.DidNotFindTwitterInSocialLoop'));

        $searchinst = in_array($instagramneedle, $contentarray);
        $this->assertFalse($searchinst, _t('SeoHeroToolSchemaCompany.FoundInstagramInSocialLoop'));
    }
}

<?php
class SeoHeroToolGoogleAnalyticTest extends FunctionalTest
{
    /*
        This class tests the behvaiour of the SeoHeroToolGoogleAnalytics and tests if this works correct with the
        SeoHeroToolController
     */
    protected static $fixture_file = 'SeoHeroToolControllerTest.yml';
    public static $use_draft_site = true;
    // Dummy Google Analytics Key
    private $googleAnalyticsKey = 'UA-12345678-1';
    // String which should be visible, if google analytics are included
    private $searchAnalytics = 'www.google-analytics.com';


    /*
      The function testProjectKey() checks if a Analtics Key for Google Analytics is defined in the yml. file.
     */
    public function testProjectKey()
    {
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $checkAnalyticsKey = $data->AnalyticsKey;
        $this->assertTrue(is_string($checkAnalyticsKey), _t('SeoHeroToolGoogleAnalyticTest.CanFindAnalyticsKey', 'Can not find your Google Analytics Key'));
    }

    /*
        The function testModusDev() checks that Google Analytics will just be active in case the environment_type and ActivateInMode setting are equal.
        Here it should just be displayed if the ActivateInMode is set to dev. In all other modes (except all) Google Analytics should be inacitve.
     */
    public function testModusDev()
    {
        Config::inst()->update('Director', 'environment_type', 'dev');
        $config = Config::inst()->get('Director', 'environment_type');
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        debug::show($response->getBody());
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('dev', 'dev')));

        // test :  not display if env is dev but display type set to live

        $data->ActivateInMode = 'live';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('dev', 'live')));

        // test : not displayed if env is dev but display type is set to test
        $data->ActivateInMode = 'test';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('dev', 'test')));

        // test: displayed if env is dev but ActivateInMode is All
        $data->ActivateInMode = 'All';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('dev', 'All')));
    }

    /*
        The function testModusLive() checks that Google Analytics will just be active in case the environment_type and ActivateInMode setting  are equal.
        Here it should just be displayed if the ActivateInMode is set to live. In all other modes (except all) Google Analytics should be inactive.
      */
    public function testModusLive()
    {
        Config::inst()->update('Director', 'environment_type', 'live');
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $data->ActivateInMode = 'live';
        $data->write();

        // Test if both settings are in live
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());

        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('live', 'live')));

        $data->ActivateInMode = 'dev';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('live', 'dev')));

        $data->ActivateInMode = 'test';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('live', 'test')));

        $data->ActivateInMode = 'All';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);

        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('live', 'All')));
    }

    /*
        The function testModusTest() checks that Google Analytics will just be active in case the environment_type and ActivateInMode setting are equal.
        In this function Google Analyits should be displayed if ActivateInMode is set to test. In all other modes (except all) Google Analytics should be inactive.
     */
    public function testModusTest()
    {
        Config::inst()->update('Director', 'environment_type', 'test');
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $data->ActivateInMode = 'test';
        $data->write();

        // Test if bot settings are in test
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('test', 'test')));

        $data->ActivateInMode = 'dev';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('test', 'dev')));

        $data->ActivateInMode = 'live';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('test', 'live')));

        $data->ActivateInMode = 'All';
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticTest.ModeTestedMode'), array('live', 'All')));
    }

    /*
        The function testAnonymizeIP() checks if the flag in the Googel Analytics code is set correctly if this option is active.
     */
    public function testAnonymizeIP()
    {
        $needle = "ga('set', 'anonymizeIp', true);";
        Config::inst()->update('Director', 'environment_type', 'dev');
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindAnonymizeInTemplate'));

        $data->AnonymizeIp = true;
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.MissingAnonymizeInTemplate'));
    }

    /*
        The function testUserOpOut() checks if the flag for OptOut from Analytics is set correctly if this option is active.
     */
    public function testUserOptOut()
    {
        $needle = "var disableStr = 'ga-disable-' + gaProperty;";
        Config::inst()->update('Director', 'environment_type', 'dev');
        $data = $this->objFromFixture('SeoHeroToolGoogleAnalytic', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticTest.FindUserOptOut'));

        $data->UserOptOut = true;
        $data->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticTest.MissingUserOptOut'));
    }
}

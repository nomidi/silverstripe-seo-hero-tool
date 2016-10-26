<?php
class SeoHeroToolGoogleAnalyticsTest extends FunctionalTest
{
    protected static $fixture_file = 'SeoHeroToolGoogleAnalyticsTest.yml';
    public static $use_draft_site = true;
    private $googleAnalyticsKey = 'UA-12345678-1';
    private $searchAnalytics = 'www.google-analytics.com';
    /*

     */
    public function testProjectKey()
    {
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        $gakey = $ga->AnalyticsKey;
        $this->assertTrue(is_string($gakey), _t('SeoHeroToolGoogleAnalyticsTest.CanFindAnalyticsKey', 'Can not find your Google Analytics Key'));
    }

    public function testModusDev()
    {
        Config::inst()->update('Director', 'environment_type', 'dev');
        $config = Config::inst()->get('Director', 'environment_type');
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        //var_dump($ga);
        //var_dump($config);
        // Test if  both settings are dev
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), 'UA-12345678-1');
        //var_dump($body);
        //var_dump($response->getBody());
        //debug::show($response);
        $this->assertTrue(is_numeric($body), 'test');

        // test if it is not displayed if env is dev but display type set to live
        /*
        $ga->ActivateInMode = 'live';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('dev', 'live')));

        // test if it not displayed if env is dev but display type is set to test
        $ga->ActivateInMode = 'test';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('dev', 'test')));

        $ga->ActivateInMode = 'All';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        var_dump($body);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('dev', 'All')));
        */
    }

    public function testModusLive()
    {
        Config::inst()->update('Director', 'environment_type', 'live');
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        $ga->ActivateInMode = 'live';
        $ga->write();

        // Test if bot settings are in live
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());

        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('live', 'live')));

        $ga->ActivateInMode = 'dev';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('live', 'dev')));

        $ga->ActivateInMode = 'test';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('live', 'test')));

        $ga->ActivateInMode = 'All';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('live', 'All')));
    }

    public function testModusTest()
    {
        Config::inst()->update('Director', 'environment_type', 'test');
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        $ga->ActivateInMode = 'test';
        $ga->write();

        // Test if bot settings are in test
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        var_dump($body);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('test', 'test')));

        $ga->ActivateInMode = 'dev';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('test', 'dev')));

        $ga->ActivateInMode = 'live';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('test', 'live')));

        $ga->ActivateInMode = 'All';
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->searchAnalytics);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.CantFindInTemplate').vsprintf(_t('SeoHeroToolGoogleAnalyticsTest.ModeTestedMode'), array('live', 'All')));
    }


    public function testAnonymizeIP()
    {
        $needle = "ga('set', 'anonymizeIp', true);";
        Config::inst()->update('Director', 'environment_type', 'dev');
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        var_dump($body);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindAnonymizeInTemplate'));

        $ga->AnonymizeIp = true;
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        var_dump($body);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.MissingAnonymizeInTemplate'));
    }

    public function testUserOptOut()
    {
        $needle = "var disableStr = 'ga-disable-' + gaProperty;";
        Config::inst()->update('Director', 'environment_type', 'dev');
        $ga = $this->objFromFixture('SeoHeroToolGoogleAnalytics', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertFalse($body, _t('SeoHeroToolGoogleAnalyticsTest.FindUserOptOut'));

        $ga->UserOptOut = true;
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolGoogleAnalyticsTest.MissingUserOptOut'));
    }
}

<?php
class SeoHeroToolGoogleAnalticsTest extends FunctionalTest
{
    protected static $fixture_file = 'SiteTreeTest.yml';
    public static $use_draft_site = true;
    
    /**
     * Test checks if an bugherd key was entered
     */

    public function testModusAll()
    {
        Config::inst()->update('SeoHeroTool', 'environment_type', 'dev');
        Config::inst()->update('Director', 'environment_type', 'dev');
        Config::inst()->update('SeoHeroTool', 'google_key', 'UA-xxxx');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $this->bugherd_string);
        $this->assertTrue(is_numeric($body), _t('BugherdHeroToolTest.CantFindInTemplate').vsprintf(_t('BugherdHeroToolTest.ModeTestedMode'), array('dev', 'dev')));
    }
}

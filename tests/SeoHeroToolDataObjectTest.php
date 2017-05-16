<?php
class SeoHeroToolDataObjectTest extends FunctionalTest
{
    public static $use_draft_site = true;

    protected static $fixture_file = array(
        'SeoHeroToolControllerTest.yml',
      );

    public function testYAMLSettingsForDay()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;
        $data = array('Title'=>array(0=>'Title', 1=>'LastEdited'), 'WithoutSpace'=>false);
        $test = $seodo->checkYAMLSettings($data);
        $this->assertTrue($test == $obj->Title.' '.date('d/m/Y'), 'The return does not match the expected value');
    }

    public function testYAMLSettingsNoSpace()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'Title', 1=>'LastEdited'), 'WithoutSpace'=>true);
        $test = $seodo->checkYAMLSettings($data);
        $this->assertTrue($test == $obj->Title.date('d/m/Y'), 'The return does not match the expected value, found space character between values');
    }

    public function testYAMLSettingsSpecificDateFormatYear()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'LastEdited'), 'DateFormat'=>'Year');
        $test = $seodo->checkYAMLSettings($data);
        $this->assertTrue($test == date('Y'));
    }

    public function testYAMLSettingsSepcificDateFormatNice()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'Created'), 'DateFormat'=>'Nice');
        $test = $seodo->checkYAMLSettings($data);
        $this->assertTrue($test == '12/12/2016 12:34pm');
    }

    public function testYAMLSettingsSpecificDateFormatSpecialSettings()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'Created'), 'DateFormat'=>'SpecialFormat', 'DateFormatting'=>'d/m');
        $test = $seodo->checkYAMLSettings($data);
        $this->assertTrue($test == '12/12');
    }

    public function testFBspecificTitle()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($this->objFromFixture('Page', 'dataobjecttest')->Link());
        $needle = '<meta property="og:title" content="'.$obj->FBTitle.'" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find a specific FBTitle within the data');
    }

    public function testTWspecificTitle()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($this->objFromFixture('Page', 'dataobjecttest')->Link());
        $needle = '<meta name="twitter:title" content="'.$obj->TwTitle.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find a specific TwTitle within the data');
    }

    public function testFBTitleDefaultTitle()
    {
        $obj = $this->objFromFixture('Page', 'objectWithBetterSiteTitle');
        $response = $this->get($this->objFromFixture('Page', 'objectWithBetterSiteTitle')->Link());
        $needle = '<meta property="og:title" content="'.$obj->BetterSiteTitle.'" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find BetterSiteTitle as og:title ');
    }

    public function testTWTitleDefaultTitle()
    {
        $obj = $this->objFromFixture('Page', 'objectWithBetterSiteTitle');
        $response = $this->get($this->objFromFixture('Page', 'objectWithBetterSiteTitle')->Link());
        $needle = '<meta name="twitter:title" content="'.$obj->BetterSiteTitle.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find BetterSiteTitle as twitter:title ');
    }
}

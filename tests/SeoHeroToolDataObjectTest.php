<?php
class SeoHeroToolDataObjectTest extends FunctionalTest
{
    public static $use_draft_site = true;

    protected static $fixture_file = array(
        'SeoHeroToolSchemaAndDataObjectControllerTest.yml',
      );

    public function testYAMLSettingsForDay()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;
        $data = array('Title'=>array(0=>'$Title', 1=>'$LastEdited'), 'WithoutSpace'=>false);
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == $obj->Title.' '.date('d/m/Y'), 'The return does not match the expected value');
    }

    public function testYAMLSettingsNoSpace()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$Title', 1=>'$LastEdited'), 'WithoutSpace'=>true);
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == $obj->Title.date('d/m/Y'), 'The return does not match the expected value, found space character between values');
    }

    public function testYAMLSettingsSpecificDateFormatYear()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$LastEdited'), 'DateFormat'=>'Year');
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == date('Y'));
    }

    public function testYAMLSettingsSepcificDateFormatNice()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$Created'), 'DateFormat'=>'Nice');
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == '12/12/2016 12:34pm');
    }

    public function testYAMLSettingsSpecificDateFormatSpecialSettings()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$Created'), 'DateFormat'=>'SpecialFormat', 'DateFormatting'=>'d/m');
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == '12/12');
    }

    /*
      Function tests that if a FBTitle is present this will be used
     */
    public function testFBspecificTitle()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<meta property="og:title" content="'.$obj->FBTitle.'" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find a specific Facebook Title within the data');
    }

    /*
      Function tests that if a FBDescription is present this will be used
     */
    public function testFBspecificDescription()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<meta property="og:description" content="'.$obj->FBDescription.'"';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Count not find a specific Facebook MetaDescription');
    }

    /*
      Functions tests that if a TwTitle is present this will be used
     */
    public function testTWspecificTitle()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<meta name="twitter:title" content="'.$obj->TwTitle.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find a specific Twitter Title within the data');
    }

    /*
      Function tests that if a TwDescription is present this will be used
    */
    public function testTWspecificDescription()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<meta name="twitter:description" content="'.$obj->TwDescription.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Counlt not find a speific Twitter MetaDescription');
    }

    /*
      Function tests that if no FBTitle is present but a BetterSiteTitle then this will be used
    */
    public function testFBTitleDefaultTitle()
    {
        $obj = $this->objFromFixture('Page', 'objectWithBetterSiteTitle');
        $response = $this->get($obj->Link());
        $needle = '<meta property="og:title" content="'.$obj->BetterSiteTitle.'" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find BetterSiteTitle as og:title ');
    }

    /*
      Function tests that if no TwTitle is present but a BetterSiteTitle then this will be used
    */
    public function testTWTitleDefaultTitle()
    {
        $obj = $this->objFromFixture('Page', 'objectWithBetterSiteTitle');
        $response = $this->get($obj->Link());
        $needle = '<meta name="twitter:title" content="'.$obj->BetterSiteTitle.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find BetterSiteTitle as twitter:title ');
    }

    /*
      Function tests that if no FBDescription is available but a default Description this will be used
    */
    public function testFBDescriptionMetaDescription()
    {
        $obj = $this->objFromFixture('Page', 'objectWithMetaDescription');
        $response = $this->get($obj->Link());
        $needle = '<meta property="og:description" content="'.$obj->MetaDescription.'"';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find standard MetaDescription as Facebook MetaDescription');
    }

    /*
      Function tests that if no TwDescription is available but a default Description this will be used
     */
    public function testTWDescriptionMetaDescription()
    {
        $obj = $this->objFromFixture('Page', 'objectWithMetaDescription');
        $response = $this->get($obj->Link());
        $needle = '<meta name="twitter:description" content="'.$obj->MetaDescription.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not finf standard MetaDescription as Twitter MetaDescription');
    }

    /*
      Function tests that the FBType which is used as og:type returns the correct values for different settings.
     */
    public function testFBType()
    {
        $obj = $this->objFromFixture('Page', 'home');
        $return = $obj->checkFBType();
        $this->assertTrue($return == $obj->FBType, 'og:type does not meet the expectations. Should be "website" but is "'.$return.'".');

        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $obj->FBType = 'article';
        $return = $obj->checkFBType();
        $this->assertTrue($return == $obj->FBType, 'og:type does not meet the expectations. Should be "article" but is "'.$return.'".');

        $obj = $this->objFromFixture('Page', 'objectWithFBTypeFromConfig');
        $obj->FBTypeOverride = 0;
        $response = $this->get($obj->Link());
        $config = Config::inst()->get('SeoHeroToolDataObject', 'Page');
        $config['FBType'] = 'product';
        Config::inst()->update('SeoHeroToolDataObject', 'Page', $config);
        $return = $obj->checkFBType();
        $this->assertTrue($return == $config['FBType'], 'og:type does not meet the expectations. Should be "product" but is "'.$return.'".');

        $obj->FBTypeOverride = 1;
        $obj->FBType = 'article';
        $return = $obj->checkFBType();
        $this->assertTrue($return == $obj->FBType, 'og:type does not meet the expectations. Should be "article" but is "'.$return.'".');
    }
}

/**
 * Test DataObject which gets created to be able to test a has_one connection on the TestPage
 */
/*
class SeoHeroToolDataObject_TestObject extends DataObject implements TestOnly
{
    private static $db = array(
        "Name" => "Varchar",
        "Title" => "Varchar"
      );
}
*/
/**
 * Test Page which gets used in the test cases.
 */
/*
class SeoHeroToolDataObject_TestPage extends Page implements TestOnly
{
    private static $has_one = array(
      "SeoHeroToolSchema_TestObject" => "SeoHeroToolSchema_TestObject"
    );

    public function myTest()
    {
        return "this seems to work";
    }
}
*/

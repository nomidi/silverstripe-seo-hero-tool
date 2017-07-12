<?php
class SeoHeroToolCheckBetterMetaDescriptionTest extends FunctionalTest
{
    protected $extraDataObjects = array(
      'SeoHeroToolDataObject_TestObject',
      'SeoHeroToolDataObject_TestPage',
    );

    protected static $fixture_file = array(
        'SeoHeroToolSchemaAndDataObjectControllerTest.yml',
        'SeoHeroToolDataObjectTest.yml'
      );

    public static $use_draft_site = true;

    /*
      Function tests that the BetterMetaDescription returns the description if set manually
     */
    public function testBetterMetaDescriptionMetaDescSetManually()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<meta name="description" content="'.$obj->MetaDescription.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find the MetaDescription Tag with the correct data which would be a manual set MetaDescription');
    }

    /*
      Function tests that the BetterMetaDescription returns the GeneratedMetaDescription if not set manually or via configuration
     */
    public function testBetterMetaDescriptionMetaDescGenMetaDesc()
    {
        $obj = $this->objFromFixture('Page', 'pageWithGenMetaDesc');
        $response = $this->get($obj->Link());
        $needle = '<meta name="description" content="'.$obj->GenMetaDesc.'">';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find the Generic Meta Description as MetaDescription');
    }

    /*
      Function tests that the BetterMetaDescription returns a string which was set as MetaDescrition via config
     */
    public function testBetterMetaDescriptionStringViaConfig()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array(0=>'Von', 1=>' Und', 2=>' Zu');
        $test = $seodo->checkBetterMetaDescriptionYaml($data);
        $testobject = 'Von Und Zu';
        $this->assertTrue($test == $testobject, 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is Von Und Zu');
    }

    /*
      Function tests that the BetterMetaDescription returns the value of a variable if this was set as MetaDescription via config
     */
    public function testBetterMetaDescriptionVarViaConfig()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array(0=>'$Title');
        $test = $seodo->checkBetterMetaDescriptionYaml($data);
        $testobject = $obj->Title;
        $this->assertTrue($test == $testobject, 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is '.$obj->title);
    }

    /*
      Function tests that the BetterMetaDescription returns the value of a function if this was set as MetaDescriptioni via config
     */
    public function testBetterMetaDescriptionFuctionViaConfig()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array(0=>'$MyTest()');
        $test = $seodo->checkBetterMetaDescriptionYaml($data);
        $testobject = $obj->MyTest();
        $this->assertTrue($test == $testobject, 'The response does not match the expected value. The response is '.$test.' while the expected value is '.$obj->MyTest());
    }

    /*
      Function tests that the BetterMetaDescription returns the value of a has-one connection if this was set as MetaDescription via config
     */
    public function testBetterMetaDescriptionHasOneViaConfig()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array(0=>'$SeoHeroToolDataObject_TestObject.Title');
        $test = $seodo->checkBetterMetaDescriptionYaml($data);
        $TestObject = $this->objFromFixture('SeoHeroToolDataObject_TestObject', 'testobject1');
        $TestobjectTitle = $TestObject->Title;
        $this->assertTrue($test == $TestobjectTitle, 'The response does not match the expected value. The response is '.$test.' while the expected value is '.$TestobjectTitle);
    }

    /*
      Function tests that the BetterMetaDescription returns a combination of values if those were set as MetaDescrition via config
     */
    public function testBetterMetaDescriptionMultipleValuesViaConfig()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;
        $data = array(0=>'$Title',1=>' test ',2=>'$MyTest()');
        $test = $seodo->checkBetterMetaDescriptionYaml($data);
        $testobject = $obj->Title.' test '.$obj->MyTest();
        $this->assertTrue($test == $testobject, 'The response does not match the expected value. The response is '.$test.' while the expected value is '.$testobject);
    }
}

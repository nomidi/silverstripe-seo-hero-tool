<?php
class SeoHeroToolDataObjectTest extends FunctionalTest
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

    public function testYAMLSettingsForDay()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;
        $data = array('Title'=>array(0=>'$Title', 1=>'$LastEdited'), 'WithoutSpace'=>false);
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == $obj->Title.' '.date('d/m/Y'), 'The return does not match the expected value');
    }

    /*
      Function testYAMLSettingsNoSpace tests if the option 'WithoutSpace' will be used correctly.
     */
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
        $this->assertTrue($test == date('Y'), 'The return does not match the expected value. Should be just the year.');
    }

    public function testYAMLSettingsSepcificDateFormatNice()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$Created'), 'DateFormat'=>'Nice');
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == '12/12/2016 12:34pm', 'The return does not match the expected Nice format.');
    }

    public function testYAMLSettingsSpecificDateFormatSpecialSettings()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$Created'), 'DateFormat'=>'SpecialFormat', 'DateFormatting'=>'d/m');
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == '12/12', 'The return does not match the specific format and is not day/month.');

        $dataNoArray = array('Title'=>'NoArrayData');
        $testNoArray = $seodo->checkTitleYAMLSettings($dataNoArray);
        $this->assertTrue($testNoArray == 'NoArrayData', 'The return does not match the expected format.');
    }

    public function testYAMLSettingsWithFunction()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$myTest()'));
        $test = $seodo->checkTitleYAMLSettings($data);
        $TestPage = new SeoHeroToolSchema_TestPage;
        $this->assertTrue($test == $TestPage->myTest(), 'It seems that there is a problem with the return value');
    }


    public function testYAMLSettingsWithHasOneConnection()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Title'=>array(0=>'$SeoHeroToolDataObject_TestObject.Title'));
        $test = $seodo->checkTitleYAMLSettings($data);
        $this->assertTrue($test == 'Title of TestObject1', 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is Title of TestObject1');
    }

    /*
      Functions tests that the function checkCanonicalSettings returns the correct value for Variables and strings
    */
    public function testCanonicalYAML()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Canonical'=>array(0=>'$Title', 1=>'von', 2=>'$Title'));
        $test = $seodo->checkCanonicalSettings($data);
        $testobject = $obj->Title.'von'.$obj->Title;
        $this->assertTrue($test == $testobject, 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is TestsitevonTestsite');


        $dataNoArray = array('Canonical'=>'testWithoutArray');
        $testNoArray = $seodo->checkCanonicalSettings($dataNoArray);
        $this->assertTrue($testNoArray == 'testWithoutArray', 'The response does not match the expected value of the title');
    }

    /*
      Functions tests that the function checkCanonicalSettings returns the correct value a function
     */
    public function testCanonicalYAMLWithFunction()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;

        $data = array('Canonical'=>array(0=>'$myTest()'));
        $test = $seodo->checkCanonicalSettings($data);
        $TestPage = new SeoHeroToolSchema_TestPage;
        $this->assertTrue($test == $TestPage->myTest(), 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is '.$TestPage->myTest());
    }

    /*
      Functions tests that the function checkCanonicalSettings returns the correct value for a HasOne Connection
     */
    public function testCanonicalWithHasOneConnection()
    {
        $obj = $this->objFromFixture('SeoHeroToolDataObject_TestPage', 'testsite');
        $seodo = new SeoHeroToolDataObject();
        $seodo = $obj;
        $data = array('Canonical'=>array(0=>'$SeoHeroToolDataObject_TestObject.Title'));
        $test = $seodo->checkCanonicalSettings($data);
        $TestObject = $this->objFromFixture('SeoHeroToolDataObject_TestObject', 'testobject1');
        $testobject = $TestObject->Title;
        $this->assertTrue($test == $testobject, 'The response does not match the expected value of the title. The response is '.$test.' while the expected value is Title of TestObject1');
    }

    /*
      Function tests that the Canonical URL is present and will be the AbsoluteLink
     */
    public function testCanonicalNothingSet()
    {
        $obj = $this->objFromFixture('Page', 'dataobjecttest');
        $response = $this->get($obj->Link());
        $needle = '<link rel="canonical" href="'.$obj->AbsoluteLink().'" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find the searched Canonical Link within the data');
    }

    /*
      Function tests that the CanonicalAll Parameter will be used correctly if set to true
     */
    public function testCanonicalAllOption()
    {
        $obj = $this->objFromFixture('Page', 'pageWithCanonicalAll');
        $response = $this->get($obj->Link());
        $needle = '<link rel="canonical" href="'.$obj->AbsoluteLink().'?all=all" />';
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), 'Could not find the searched Canonical Link with ?all=all option within the data');
    }
}
/**
 * Test DataObject which gets created to be able to test a has_one connection on the TestPage
 */

class SeoHeroToolDataObject_TestObject extends DataObject implements TestOnly
{
    private static $db = array(
        "Name" => "Varchar",
        "Title" => "Varchar"
      );
}
/**
 * Test Page which gets used in the test cases.
 */

class SeoHeroToolDataObject_TestPage extends Page implements TestOnly
{
    private static $has_one = array(
      "SeoHeroToolDataObject_TestObject" => "SeoHeroToolDataObject_TestObject"
    );

    public function myTest()
    {
        return "this seems to work";
    }

    public function myFBImage()
    {
        return 'SomePath';
    }

    public function myTWImage()
    {
        return 'someOtherPath';
    }
}

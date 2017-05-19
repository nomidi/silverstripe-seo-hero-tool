<?php
class SeoHeroToolSchemaDataObjectTest extends FunctionalTest
{
    protected $extraDataObjects = array(
    'SeoHeroToolSchema_TestObject',
    'SeoHeroToolSchema_TestPage',
  );

    protected static $fixture_file = array('SeoHeroToolSchemaAndDataObjectControllerTest.yml', 'SeoHeroToolSchemaDataObjectTest.yml');
    public static $use_draft_site = true;

    /**
     * testSchemaDataObject creates for the SeoHeroToolSchema_TestPage a Schema which contains normal text but also the
     * title of the page and the title of the connected dataobject SeoHeroToolSchema_TestObject
     * @return [bool]
     */

    public function testSchemaDataObject()
    {
        $config = Config::inst()->get('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage');
        $config['@context'] = 'http://www.schema.org';
        $config['@type'] = 'LocalBusiness';
        $array = array('Title'=>'$Title', 'HasOneConnection'=>'$SeoHeroToolSchema_TestObject.Title');
        $config['functiontest'] = '$myTest()';
        $config['test'] = $array;
        Config::inst()->update('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage', $config);

        $page = $this->objFromFixture('SeoHeroToolSchema_TestPage', 'testsite');
        $object = $page->SeoHeroToolSchema_TestObject();

        $response = $this->get($page->Link());
        $responseBody = $response->getBody();

        $searchstringVariable = '"Title": "'.$page->Title.'"';
        $bodyHasVariable = strpos($responseBody, $searchstringVariable);
        $this->assertTrue(is_numeric($bodyHasVariable), "Did not find value for Title in the response.");

        $searchstringHasOneConnection = '"HasOneConnection": "'.$object->Title.'"';
        $bodyHasOneConnection = strpos($responseBody, $searchstringHasOneConnection);
        $this->assertTrue(is_numeric($bodyHasOneConnection), "Did not find value for HasOneConnection in the response.");

        $searchstringContext = '"@type": "LocalBusiness"';
        $bodyHasContext = strpos($responseBody, $searchstringContext);
        $this->assertTrue(is_numeric($bodyHasContext), "Did not find the key and value for type in the response.");

        $searchstringFunction = '"functiontest": "'.$page->myTest().'"';
        $bodyHasFunction = strpos($responseBody, $searchstringFunction);
        $this->assertTrue(is_numeric($bodyHasFunction), "Did not find the return of the myTest function in the response.");
    }

    /**
     * Method testSchemaVariableNotFound tests that there is no schema object if one or more non exisiting
     * or empty Variables are accessed
     * @return [bool]
     */
    public function testSchemaVariableNotFound()
    {
        $config = Config::inst()->get('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage');
        $config['@context'] = 'http://www.schema.org';
        $config['@type'] = 'LocalBusiness';
        $array = array('Title'=>'$TitleA', 'HasOneConnection'=>'$SeoHeroToolSchema_TestObject.Title');
        $config['functiontest'] = '$myTest()';
        $config['test'] = $array;
        Config::inst()->update('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage', $config);

        $page = $this->objFromFixture('SeoHeroToolSchema_TestPage', 'testsite');
        $object = $page->SeoHeroToolSchema_TestObject();

        $response = $this->get($page->Link());
        $responseBody = $response->getBody();

        $searchstringVariable = '"Title": ""';
        $bodyHasVariable = strpos($responseBody, $searchstringVariable);
        $this->assertFalse($bodyHasVariable, 'Found json with Title information which should not be displayed');
    }

    /**
     * Method testSchemaHasOneNotFound tests that there is no schema object returned if one ore more has_one connection are not
     * existing or empty.
     * @return [bool]
     */
    public function testSchemaHasOneNotFound()
    {
        $config = Config::inst()->get('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage');
        $config['@context'] = 'http://www.schema.org';
        $config['@type'] = 'LocalBusiness';
        $array = array('Title'=>'$Title', 'HasOneConnection'=>'$SeoHeroToolSchema_TestObject.TitleA');
        $config['functiontest'] = '$myTest()';
        $config['test'] = $array;
        Config::inst()->update('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage', $config);

        $page = $this->objFromFixture('SeoHeroToolSchema_TestPage', 'testsite');
        $object = $page->SeoHeroToolSchema_TestObject();

        $response = $this->get($page->Link());
        $responseBody = $response->getBody();

        $searchstringConnection = '"HasOneConnection": ""';
        $bodyHasConnection = strpos($responseBody, $searchstringConnection);
        $this->assertFalse($bodyHasConnection, 'Found json with Title information of the DataObject which should not be displayed');
    }


    /**
     * Method testSchemaNonExistingFunction tests that there is no schema object reurned if one ore more methods are accessed which
     * are not existing or return an empty value.
     */
    public function testSchemaNonExistingFunction()
    {
        $config = Config::inst()->get('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage');
        $config['@context'] = 'http://www.schema.org';
        $config['@type'] = 'LocalBusiness';
        $array = array('Title'=>'$Title', 'HasOneConnection'=>'$SeoHeroToolSchema_TestObject.Title');
        $config['functiontest'] = '$myTestABC()';
        $config['test'] = $array;
        Config::inst()->update('SeoHeroToolSchemaDataObject', 'SeoHeroToolSchema_TestPage', $config);

        $page = $this->objFromFixture('SeoHeroToolSchema_TestPage', 'testsite');
        $object = $page->SeoHeroToolSchema_TestObject();

        $response = $this->get($page->Link());
        $responseBody = $response->getBody();

        $searchstringFunction = '"functiontest":';
        $bodyHasFunction = strpos($responseBody, $searchstringFunction);
        $this->assertFalse($bodyHasFunction, "Found json with functiontest which should not be displayed.");
    }
}

/**
 * Test DataObject which gets created to be able to test a has_one connection on the TestPage
 */
class SeoHeroToolSchema_TestObject extends DataObject implements TestOnly
{
    private static $db = array(
        "Name" => "Varchar",
        "Title" => "Varchar"
      );
}

/**
 * Test Page which gets used in the test cases.
 */
class SeoHeroToolSchema_TestPage extends Page implements TestOnly
{
    private static $has_one = array(
      "SeoHeroToolSchema_TestObject" => "SeoHeroToolSchema_TestObject"
    );

    public function myTest()
    {
        return "this seems to work";
    }
}

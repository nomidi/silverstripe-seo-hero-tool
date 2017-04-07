<?php
class SeoHeroToolDataObjectTest extends FunctionalTest
{
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
}

<?php
class SeoHeroToolRedirectTest extends FunctionalTest
{
    protected static $fixture_file = 'SeoHeroToolControllerTest.yml';
    public static $use_draft_site = true;

    public function testValidateCorrectObj()
    {
        $data = $this->objFromFixture('SeoHeroToolRedirect', 'default');
        $data->debug = true;
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertTrue($valid, _t('SeoHeroToolRedirectTest.UnexpectedIssue', 'Unexpected Issue - No error should occur'));
    }

    public function testValidateEmptyOldLinkName()
    {
        $data = $this->objFromFixture('SeoHeroToolRedirect', 'default');
        $data->debug = true;
        $data->OldLinkName = '';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertFalse($valid, _t('SeoHeroToolRedirect.MissingOldLinkName', 'OldLinkName is not empty'));
    }

    public function testValidateBlankInOldLinkName()
    {
        $data = $this->objFromFixture('SeoHeroToolRedirect', 'default');
        $data->debug = true;
        $data->OldLinkName = 'test test';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertFalse($valid, _t('SeoHeroToolRedirect.SpaceInOldLinkName', 'OldLinkName should contain a blank WITHIN the string.'));
    }

    public function testValidateBlankInNewLinkName()
    {
        $data = $this->objFromFixture('SeoHeroToolRedirect', 'default');
        $data->debug = true;
        $data->NewLinkName = 'test test';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertFalse($valid, _t('SeoHeroToolRedirectTest.SpaceInNewLinkName', 'NewLinkName should contain a blank WIHTIN the string.'));
    }

    public function testSameOldLinkName()
    {
        $data = new SeoHeroToolRedirect;
        $data->debug = true;
        $data->OldLinkName = 'test/xyz';
        $data->NewLinkName = 'test/asd';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertFalse($valid, _t('SeoHeroToolRedirectTest.SameOldLinkName', 'OldLinkName should just be allowed once'));
    }

    public function testUpdateEntry()
    {
        $data = $this->objFromFixture('SeoHeroToolRedirect', 'default');
        $data->debug = true;
        $data->NewLinkName = 'test/newLink';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertTrue($valid, _t('SeoHeroToolRedirectTest.UpdateEntry', 'It should be possible to update a NewLinkName for an existing old Link'));
    }

    public function testNoNewLinkGiven()
    {
        $data = new SeoHeroToolRedirect;
        $data->debug = true;
        $data->OldLinkName = 'test/abc';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertFalse($valid, _t('SeoHeroToolRedirectTest.NoNewLinkGiven', 'Either the new field NewLinkName must contain content or the has_one connection NewLink needs to be used.'));
    }

    public function testNewEntryWithNewLinkName()
    {
        $data = new SeoHeroToolRedirect;
        $data->debug = true;
        $data->OldLinkName = 'test/abc';
        $data->NewLinkName = 'test/aNewLink';
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertTrue($valid, _t('SeoHeroToolRedirectTest.NewEntryWithNewLinkName', 'It should be enough for an entry to have an OldLinkName and a NewLinkName'));
    }

    public function testNewEntryWithNewLink()
    {
        $data = new SeoHeroToolRedirect;
        $data->debug = true;
        $data->OldLinkName = 'test/abc';
        $NewLink = $this->objFromFixture('Page', 'redirectlink');
        $data->NewLink()->ID = $NewLink->ID;
        $testresult = $data->validate();
        $valid = $this->accessProtected($testresult, 'isValid');
        $this->assertTrue($valid, _t('SeoHeroToolRedirectTest.NewEntryWithNewLink', 'It should be enough for an entry to have an OldLinkName and a NewLink via SiteTree'));
    }

    public function accessProtected($obj, $prop)
    {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
}

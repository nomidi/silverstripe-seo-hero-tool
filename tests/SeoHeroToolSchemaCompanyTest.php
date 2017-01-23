<?php
class SeoHeroToolSchemaCompanyTest extends FunctionalTest
{
    protected static $fixture_file = 'SeoHeroToolSchemaCompanyTest.yml';
    public static $use_draft_site = true;






    public function testOrganizationType()
    {
        $needle = "@type";
        $ga = $this->objFromFixture('SeoHeroToolSchemaCompany', 'default');
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertFalse($body, _t('SeoHeroToolSchemaCompany.FindOrganizationTypeInTemplate'));

        $ga->OrganizationType = "";
        $ga->write();
        $response = $this->get($this->objFromFixture('Page', 'home')->Link());
        $body = strpos($response->getBody(), $needle);
        $this->assertTrue(is_numeric($body), _t('SeoHeroToolSchemaCompany.MissingOrganizationTypeInTemplate'));
    }
}

# SeoHeroTool

The SeoHeroTool offers options to control the Meta Information of a Website better than with the default Silverstripe possibilities.

## Overview
 - GoogleAnalytics
 - Schema.org
 - Social Media
 - Keywords and Meta
 - Robots and .htaccess Editor

## Google Analytics

Add
```
SeoHeroTool:
  google_key: 'UA-xxx'
  environment_type: 'dev'
  member_status: true
  anonymizeIP: false
  loadTime: false
  userOptOut: false
```
to your config.yml. Replace UA-xxx with your Google Analytics Universal ID.
Environment type can be either 'dev','test','live' or 'all'.
Member status checks if logged in Members are also counted. true counts them,
false not.
anonymizeIP defines if the IP Address will be transmitted anonymized, which is
the default setting.

## Schema.org

Data entered here will be used to create a correct schema file which is useful for search engines.

Furthermore it is possible via the .yml configuration to create own schemas. In those schemas it is possible
to access variables of the page and to access variables of has_one connections.

The below example shows all possibilities which can be used within the .yml creation.
The normal fields should be pretty self-explanatory. Just keep in mind that the first part should match
exactly the definition on schema.org.
name, streetAddress and addressLocality are the interesting parts.

name has $Title. This means that after processing there will be displayed the Title-Varibale from the DummyPage.
streetAddress has the value of the method getStreet() which must be either part of the class or any parent-class. This way it is possible to create return values of basically any kind.
$DummyOBject.Title means, that DummyPage has a has_one connection with DummyObject. And from this dataobject the Title will be used.

If any of the variable/connections/methods returns nothing or an empty value the whole json object will not be created.

```
SeoHeroToolSchemaDataObject:
  DummyPage:
    @context: "http://www.schema.org"
    @type: "LocalBusiness"
    address:
      @type: "PostalAddress"
      addressLocality: $DummyObject.Title
      postalCode: "12345"
      streetAddress: $getStreet()
    name: $Title
    telephone: "01234 23234234"
    email: "mail@testfirma.de"
```

This will create when the page is rendered the following json in the schema.org format. It is important that if you are
willing to use this feature you ensure that you know about the correct structure of the schema you want to represent.

```
<script type="application/ld+json">
 {
   "@context": "http:\/\/www.schema.org",
   "@type": "LocalBusiness",
   "address": {
       "@type": "PostalAddress",
       "addressLocality": "Dummy Object Title",
       "postalCode": "12345",
       "streetAddress": "Am Bruch 1"
   },
   "name": "Dummy Page Title",
   "telephone": "01234 23234234",
   "email": "mail@testfirma.de"
}
 </script>
```
## SocialMedia

SocialMedia sites can be entered and for examples looped later on the website. this is useful to have all important social media data in one place.

To output the Social Media Loop simply loop the function $SocialLoop.
By default the loop will be sorted by the sorting which can be changed in the backend. But it is possible to sort it alphabetical with the default
Silverstripe functions which would look like this $SocialLoop.Sort(Name,ASC)

## Keywords and Metadata

### Generating of the MetaDataTitle and FB Type

SeoHeroTool and MetaDataTitle.
Please replace in your template in the header section the title output.
You can add in your config.yml default values for the title for certain types of page.

To do so please add the following:
```
SeoHeroToolDataObject:
  Page:
    Title:
      - $Title
      - $LastEdited
    WithoutSpace: false
  TestPage:
    Title:
      - $Title
      - " at "
      - $LastEdited
      - $MyTest()
      - $TestObject.Title
    DateFormat: SpecialFormat
    DateFormatting: d/m
    WithoutSpace: true
    SiteConfigTitle: true
    FBType: article
```

All Pages with the Type Page will be displayed in the title in this case with
the Title and the Date of the last Edit. Between both there will be a space.
All Pages with the Type TestPage will have the field Title, followed by an "at", followed by the time of the last edit of the page, followed by the return of the function MyTest() and lastly the Title of the has-one connection TestObject. There will be no spacing character between each entry. The Pages with the Type of TestPage will also have the SiteConfigTitle at the end.

Options for DateFormat are : Nice24, Year, Nice and SpecialFormat.
If SpecialFormat is set, then the setting DateFormatting determines how the
date will be formatted. In this example just the day and month of the date will be
displayed.
The option FBType defines the og:type attribute of the page type. The og:type is part of the OpenGraph Protocol.
By default each page is a 'website', but it can also be for example an 'article', which is usefuel for Blogs or News Posts or it can be a 'product'.

It is always possible to overrite a configuraiton title by giving a website in the backend via the SeoHeroTool a specific title.

Please keep in mind, that in the default theme the Sitename will always be attached at the end of the title.
If you use this option with the SeoHeroToolDataObject the Sitename will appear twice, so please check your
theme and remove the Sitename in the title if you want to control it via SeoHeroTools.

## Robots and .htaccess Editor

At the moment it is just possible in this section to create 301 Redirects. Those redirects can be used to
forward old pages which are not exisiting anymore to a new page. The browser will then receive 404 error message but
will be forwarded to the new page.

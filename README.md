# SeoHeroTool

[![Build Status](https://travis-ci.org/nomidi/silverstripe-seo-hero-tool.svg?branch=master)](https://travis-ci.org/nomidi/silverstripe-seo-hero-tool)

The SeoHeroTool offers options to control the Meta Information of a Website better than with the default Silverstripe possibilities.

## Overview
 - GoogleAnalytics
 - Schema.org
 - Social Media
 - SeoHeroTool Tab
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

SocialMedia sites can be entered and for examples looped later on the website. This is useful to have all important social media data in one place.

To output the Social Media Loop simply loop the function $SocialLoop.
By default the loop will be sorted by the sorting which can be changed in the backend. But it is possible to sort it alphabetical with the default
Silverstripe functions which would look like this $SocialLoop.Sort(Name,ASC)

## SeoHeroTool Tab

Once the SeoHeroTool is installed you will see on all pages a tab called SeoHeroTool. Within this tab you can configure the following:

- SEO Title Tag
- Keywords (used in SeoHeroTool Pro)
- MetaData
- Facebook
- Twitter

Furthermore the SeoHeroTool Tab gives you a preview on how your website will appear in a search result.

### SEO Title Tag

The SEO Title defines the value for the Title attribute. By default this is the title of this site.
It is possible via the SeoHeroToolDataObject to define a title based on data fields. This is explained in detail in the chapter
Generating of the MetaDataTitle and FB Type.

### Keywords

The Keywords are not used in the SeoHeroTool right now. At the moment they are used in the SeoHeroTool Pro in the keyword analysis and general webpage analysis.

### MetaData

Metadata contains all necessary MetaData Information. This includes the following:
- information for search robots
- possiblity to add a canonical URL
- Meta Description
- langhref attribute if this is a multilingual page.
- Google Schema Org Data if defined in SeoHeroToolSchemaDataObject

### Facebook

For a better sharing experience it is possible to enter data for Facebook Sharing.
This includes the Title for the page, an Image for Facebook which will be shown if this page gets shared, the Type of this Site and a Description for Facebook.
As Default value for Title and Description the SEO Title and Meta Description will be used.
The default Type for the site will be Website. This can be also configured via the SeoHeroToolDataObject.
In case that a specific page shall have a different Type it is possible to overturn the one from the configuration via a checkbox.  

### Twitter

For a better sharing experience it is possible to enter data for Twitter Sharing.
This includes the Title for the page, an Image for Twitter and a Description.
As Default value for Title and Description the SEO Title and Meta Description will be used.

## Keywords and Metadata

### Generating of the MetaDataTitle and FB Type

With an installed SeoHeroTool the Title for the page which appear in within the <title>-Tag will always be generated with the Method MetaTitle().
Therefore the Variable $Title is not necessary anymore within the <title>-Tag and can be removed. But this is not necessary.

It is possible to generate the MetaTitle for a website via the config.yml file.
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
By default each page is a 'website', but it can also be for example an 'article', which is usefuel for Blogs or News Posts or it can be a 'product'. If a specific page should have a different Type than all other pages with the same page type it is possible to overturn the configuration FBType in the SeoHeroTool for this specific page.

Please keep in mind, that in the default theme the Sitename will always be attached to the pagename at the end of the title.
If you want to use the SiteConfigTitle option with the SeoHeroToolDataObject the Sitename will appear twice. Please check your
theme and remove the Sitename in the <title>-tag if you want to control it via SeoHeroTools.

## Robots and .htaccess Editor

At the moment it is just possible in this section to create 301 Redirects. Those redirects can be used to
forward old pages which are not exisiting anymore to a new page. The browser will then receive 404 error message but
will be forwarded to the new page.

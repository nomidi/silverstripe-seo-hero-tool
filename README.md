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

Data entered here will be used to create a correct schema file which is useful for searchengines.

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
      - Title
      - LastEdited
    WithoutSpace: false
  TestPage:
    Title:
      - Title
      - LastEdited
    DateFormat: SpecialFormat
    DateFormatting: d/m
    WithoutSpace: true
    SiteConfigTitle: true
    FBType: article
```
All Pages with the Type Page will be displayed in the title in this case with
the Title and the Date of the last Edit. Between both there will be a space.
All Pages with the Type TestPage will have both fields, but there will be no
space between the fields. The Pages with the Type of TestPage will also have the
SiteConfigTitle at the end.
The default Title for those Page Types will just be used if there is no site
specific BetterTitle given.
Options for DateFormat are : Nice24, Year, Nice and SpecialFormat.
If SpecialFormat is set, then the setting DateFormatting determines how the
date will be formatted. In this example just the day and month of the date will be
displayed.
The option FBType defines the og:type attribute of the page type. The og:type is part of the OpenGraph Protocol.
By default each page is a 'website', but it can also be for example an 'article', which is usefuel for Blogs or News Posts or it can be a 'product'.


Please keep in mind, that in the default theme the Sitename will always be attached at the end of the title.
If you use this option with the SeoHeroToolDataObject the Sitename will appear twice, so please check your
theme and remove the Sitename in the title if you want to control it via SeoHeroTools.

## Robots and .htaccess Editor

At the moment it is just possible in this section to create 301 Redirects. Those redirects can be used to
forward old pages which are not exisiting anymore to a new page. The browser will then receive 404 error message but
will be forwarded to the new page.

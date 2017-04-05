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

SeoHeroTool and MetaDataTitle.
Please replace in your template in the header section the title output.
You can add in your config.yml default values for the title for certain pagetypes.

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
    WithoutSpace: true
    SiteConfigTitle: true
```
All Pages with the Type Page will be displayed in the title in this case with
the Title and the Date of the last Edit. Between both there will be a space.
All Pages with the Type TestPage will have both fields, but there will be no
space between the fields. The Pages with the Type of TestPage will also have the
SiteConfigTitle at the end.
The default Title for those Page Types will just be used if there is no site
specific BetterTitle given. 

<% with SchemaCompany %>
  <% if OrganizationType %>
    <script type="application/ld+json">
    { "@context" : "http://schema.org",
      "@type" : "$OrganizationType"
      <% if $Link %>,"url" : "$Link"<% end_if %>
      <% if $Company %>,"legalName": "$Company"<% end_if %>
      <% if $Mail %>,"email":"$Mail"<% end_if %>
      <% if $Company %>,"name" : "$Company"<% end_if %>
      <% if $Logo %>,"logo": "{$BaseHref}$Logo.RelativeLink"<% end_if %>
      <% if $VatID %>,"vatID": "$VatID"<% end_if %>
      <% if $Postal && $Location && $Street && $Country %>
       ,"address": {
        "@type": "PostalAddress",
        "addressLocality": "$Location",
        "postalCode": "$Postal",
        "streetAddress": "$Street<% if $HouseNmbr != $Street %> $HouseNmbr<% end_if %>",
        "addressCountry": "$Country"
      }
      <% end_if %>
      <% if Tel %>
      ,"contactPoint" : [
        { "@type" : "ContactPoint",
          "telephone" : "$Tel",
          "contactType" : "customer service"
        } ]
      <% end_if %>
      <% if $SeoHeroToolSocialLinks %>
      ,"sameAs":[
        <% loop $SeoHeroToolSocialLinks %>
        "{$Link}"<% if not $Last %>,<% end_if %>
        <% end_loop %>
      ]
      <% end_if %>
      <% if $OpeningHoursInSchema %>
        ,"openingHoursSpecification": [
          <% loop $SeoHeroToolOpeningHours %>
            {
              "@type": "OpeningHoursSpecification",
              <% if $Close %>"closes": "{$Close}",<% end_if %>
              <% if $Open %>"opens": "{$Open}",<% end_if %>
              "dayOfWeek": "http://schema.org/{$Day}"
            }<% if not Last %>,<% end_if %>
          <% end_loop %>
        ]
      <% end_if %>

      }
    </script>

  <% if $Latitude && $Longitude %>
  <script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "Place",
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "$Latitude",
      "longitude": "$Longitude"
    },
    <% if $Company %>"name": "$Company"<% end_if %>
  }
  </script>
  <% end_if %>
<% end_if %>
<% end_with %>

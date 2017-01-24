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
      <% if $Postal && $Location && $Street %>
       ,"address": {
        "@type": "PostalAddress",
        "addressLocality": "$Location",
        "postalCode": "$Postal",
        "streetAddress": "$Street<% if $HouseNmbr != $Street %> $HouseNmbr<% end_if %>"
      }
      <% end_if %>
      <% if Tel %>
      ,"contactPoint" : [
        { "@type" : "ContactPoint",
          "telephone" : "$Tel",
          "contactType" : "customer service"
        } ]
      <% end_if %>


      }
    </script>
  <% end_if %>
<% end_with %>

<div>Google Analytics
  GAKEY: $gakey<br/>
  <a href="$GoogleAnalyitcEditLink">Bearbeiten</a>
</div>

<div>
  Zweiter Block
  <% if $SocialLinks %>
    <ul>
    <% loop $SocialLinks %>
      $Name<br/>
    <% end_loop %>
    </ul>
  <% end_if %>
  <a href="$SocialLinksEditLink">Bearbeiten</a>
 </div>

<div>Dritter Block </div>

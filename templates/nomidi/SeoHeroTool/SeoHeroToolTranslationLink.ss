<% if Translations %>
	<link rel="alternate" hreflang="$Locale.RFC1766" href="$AbsoluteLink" />
	<% loop Translations %>
		<link rel="alternate" hreflang="$Locale.RFC1766" href="$AbsoluteLink" />
	<% end_loop %>
<% end_if %>

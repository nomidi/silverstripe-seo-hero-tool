<%-- MetaDescription --%>
<% if $BetterMetaDescription %>
	<meta name="description" content="$BetterMetaDescription">
<% end_if %>
<%-- Facebook --%>
<meta property="og:title" content="<% if FBTitle %>$FBTitle<% else %>$MetaTitle<% end_if %>" />
<meta property="og:url" content="$AbsoluteLink" />
<meta property="og:type" content="$CheckFBType" />
<% if $SMPreviewImage('FB') %><meta property="og:image" content="$SMPreviewImage('FB')" /><% end_if %>
<% if FBDescription || BetterMetaDescription %> <meta property="og:description" content="<% if FBDescription %>$FBDescription<% else %>$BetterMetaDescription<% end_if %>"><% end_if %>
<meta property="article:published_time" content="$Created.format('Y-MM-dd')" />
<meta property="article:modified_time" content="$LastEdited.format('Y-MM-dd')" />
<meta property="og:updated_time" content="$LastEdited.format('Y-MM-dd')" />

<%-- Twitter --%>
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<% if TwTitle %>$TwTitle<% else %>$MetaTitle<% end_if %>">
<% if TwDescription || BetterMetaDescription %> <meta name="twitter:description" content="<% if TwDescription %>$TwDescription<% else %>$BetterMetaDescription<% end_if %>"> <% end_if %>
<% if $SMPreviewImage('TW') %><meta name="twitter:image" content="$SMPreviewImage('TW')"><% end_if %>

<% if SeoHeroToolSocialMediaChannels %>
	<% loop SeoHeroToolSocialMediaChannels %>
		<% if Network == "Facebook" %>
			<meta property="article:publisher" content="$Link" />
		<% else_if Network == "Twitter" %>
			<meta name="twitter:site" content="@{$Username}" />
			<meta name="twitter:creator" content="@{$Username}" />
		<% else_if Network == "Google+" %>
			<link rel="publisher" href="$Link"/>
		<% end_if %>
	<% end_loop %>
<% end_if %>

<% if BetterMetaDescription %>
	<meta name="description" content="$BetterMetaDescription">
<% end_if %>
<%-- Facebook --%>
<meta property="og:title" content="<% if FBTitle %>$FBTitle<% else %>$MetaTitle<% end_if %>" />
<meta property="og:url" content="$AbsoluteLink" />
<meta property="og:type" content="$CheckFBType" />
<% if FBImage %><meta property="og:image" content="$FBImage.AbsoluteURL" /><% end_if %>
<% if FBDescription || BetterMetaDescription %> <meta property="og:description" content="<% if FBDescription %>$FBDescription<% else %>$BetterMetaDescription<% end_if %>"><% end_if %>
<meta property="article:published_time" content="$Created.format('c')" />
<meta property="article:modified_time" content="$LastEdited.format('c')" />
<meta property="og:updated_time" content="$LastEdited.format('c')" />


<%-- Twitter --%>
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<% if TwTitle %>$TwTitle<% else %>$MetaTitle<% end_if %>">
<% if TwDescription || BetterMetaDescription %> <meta name="twitter:description" content="<% if TwDescription %>$TwDescription<% else %>$BetterMetaDescription<% end_if %>"> <% end_if %>
<% if TwImage %><meta name="twitter:image" content="$TwImage.AbsoluteURL"><% end_if %>


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

<% if $Follow == 'in' %>
	<meta name="robots" content="index, nofollow">
<% else_if $Follow == 'i' %>
	<meta name="robots" content="index">
<% else_if $Follow == 'nf' %>
	<meta name="robots" content="noindex, follow">
<% else_if $Follow == 'nn' %>
	<meta name="robots" content="noindex, nofollow">
<% else_if $Follow == 'n' %>
	<meta name="robots" content="noindex">
<% else %>
	<meta name="robots" content="index, follow">
<% end_if %>

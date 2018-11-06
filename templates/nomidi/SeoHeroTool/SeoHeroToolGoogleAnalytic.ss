<% loop GoogleAnalytics %>
  <% if $AnalyticsKey %>

    <% if $UserOptOut %>
      <script>
        var gaProperty = '$AnalyticsKey';
        var disableStr = 'ga-disable-' + gaProperty;
        if (document.cookie.indexOf(disableStr + '=true') > -1) {
          window[disableStr] = true;
        }
        function gaOptout() {
          document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
          window[disableStr] = true;
        }
      </script>
    <% end_if %>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '$AnalyticsKey', 'auto');
      ga('send', 'pageview');
      <% if $AnonymizeIp %>ga('set', 'anonymizeIp', true);<% end_if %>

    </script>

  <% end_if %>
<% end_loop %>

<strong>Google</strong>
<div class="seo-hero-tool-snippet-google">
    <a class="seo-hero-tool-snippet-google__title" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">$Title</a>
    <div class="seo-hero-tool-snippet-google__url">$AbsoluteLink</div>
    <p class="seo-hero-tool-snippet-google__meta">$MetaDesc</p>
</div>

<div class="seo-hero-tool-snippet-container">
  <div>
  <strong>Facebook</strong> <span>(1200x627)</span>
    <div class="seo-hero-tool-snippet-fb">
      <a class="seo-hero-tool-snippet-fb__link" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">
        <% if $FBImage %>
          <img src="$FBImage" class="seo-hero-tool-snippet-fb__image">
        <% end_if %>
        <div class="seo-hero-tool-snippet-fb__text">
          <div class="seo-hero-tool-snippet-fb__text__title">
            <% if FBTitle %>$FBTitle<% else %>$MetaTitle<% end_if %>
          </div>
          <div class="seo-hero-tool-snippet-fb__text__content">
            <% if FBDescription %>$FBDescription<% else %>$MetaDesc<% end_if %>
          </div>
          <div class="seo-hero-tool-snippet-fb__text__url">
            $Server
          </div>
        </div>
      </a>
    </div>
  </div>
  <div>
  <strong>Twitter</strong> <span>(600x314)</span>
    <div class="seo-hero-tool-snippet-tw">
      <a class="seo-hero-tool-snippet-tw__link" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">
        <% if $TWImage %>
          <img src="$TWImage" class="seo-hero-tool-snippet-tw__image">
        <% end_if %>
        <div class="seo-hero-tool-snippet-tw__text">
          <div class="seo-hero-tool-snippet-tw__text__title">
            <% if FBTitle %>$FBTitle<% else %>$MetaTitle<% end_if %>
          </div>
          <div class="seo-hero-tool-snippet-tw__text__content">
            <% if FBDescription %>$FBDescription<% else %>$MetaDesc<% end_if %>
          </div>
          <div class="seo-hero-tool-snippet-tw__text__url">
            $Server
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<strong>Google</strong>
<div class="seo-hero-tool-snippet-google">
    <a class="seo-hero-tool-snippet-google__title" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">$Title</a>
    <div class="seo-hero-tool-snippet-google__url">$AbsoluteLink</div>
    <p class="seo-hero-tool-snippet-google__meta">$MetaDesc</p>
</div>

<div class="seo-hero-tool-snippet-container">
  <div>
  <strong>Facebook</strong>
    <div class="seo-hero-tool-snippet-fb">
      <a class="seo-hero-tool-snippet-fb__link" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">
        <% if $FBImage %>
          <div style="background-image:url($FBImage)" class="seo-hero-tool-snippet-fb__image">
          </div>
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
  <strong>Twitter</strong>
    <div class="seo-hero-tool-snippet-tw">
      <a class="seo-hero-tool-snippet-tw__link" href="$AbsoluteLink" rel="noopener nofollow" target="_blank">
        <% if $TWImage %>
          <div style="background-image:url($TWImage)" class="seo-hero-tool-snippet-tw__image">
          </div>
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

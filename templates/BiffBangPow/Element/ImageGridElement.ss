<div class="container">
    <% if $Title && $ShowTitle %>
        <h2 class="imagegrid-title mb-3 mb-xl-4">$Title</h2>
    <% end_if %>
    <% if $Content %>
        <div class="row">
            <div class="col-12">
                $Content
            </div>
        </div>
    <% end_if %>
    <% if $Items %>
        <div class="row mb-4 row-cols-$ColsMobile row-cols-md-$ColsTablet row-cols-lg-$ColsDesktop row-cols-xl-$ColsLarge">

            <% loop $Items %>
                <div class="col mb-3">
                    <% if $LinkData %>
                    <a href="$LinkData.Link"
                    <% if $LinkData.Target == 'external' %> target="_blank" rel="noopener"<% end_if %>
                    <% if $LinkData.Target == 'download' %> download="download"<% end_if %>
                    >
                    <% end_if %>
                    <picture>
                        <% with $Image.ScaleMaxWidth(300) %>
                            <source type="image/webp" srcset="$Format('webp').Link">
                            <img class="img-fluid image-item lazyload" title="$Title" alt="$Title" data-src="$Link"
                                 src=""
                                 loading="lazy" width="$Width" height="$Height"/>
                        <% end_with %>
                    </picture>
                    <% if $LinkData %>
                    </a>
                    <% end_if %>
                </div>
            <% end_loop %>
        </div>

    <% end_if %>
</div>




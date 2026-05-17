<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($sitemaps as $sitemap)
    <sitemap>
        <loc>{{ $sitemap['loc'] }}</loc>
        <lastmod>{{ is_object($sitemap['lastmod']) ? $sitemap['lastmod']->toAtomString() : $sitemap['lastmod'] }}</lastmod>
    </sitemap>
@endforeach
</sitemapindex>

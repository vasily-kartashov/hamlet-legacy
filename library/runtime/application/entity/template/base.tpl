<!DOCTYPE html>
<html class="no-js {$base.currentLanguage} {$base.textDirection}" prefix="og: http://ogp.me/ns#" dir="{$base.textDirection}" lang="{$base.currentLanguage}">
    <head>
        <title>{$base.siteTitle}</title>

        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//google-analytics.com">

        {*<link rel="home" title="{$base.siteTitle}" href="{$base.completePath}">*}
        {*<link rel="contents" title="{$sitemap.title}" href="{$sitemap.completePath}">*}
        {*<link rel="author" href="https://plus.google.com/{$base.officialGooglePlusId}">*}
        {block "links"}{/block}

        <meta name="description" content="{$meta.description}">
        <meta name="keywords" content="{$meta.keywords}">
        <meta name="viewport" content="width=1024">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        {*<meta name="fb:app_id" content="{$facebook.appId}">*}

        {block "opengraph"}
            <meta property="og:title" content="{$base.siteTitle}">
            <meta property="og:type" content="website">
            <meta property="og:description" content="{$meta.description}">
            {*<meta property="og:url" content="{$base.shareUrl}">*}
            {*<meta property="og:image" content="{$meta.image.src}">*}
            {*<meta property="og:image:width" content="{$meta.image.width}">*}
            {*<meta property="og:image:height" content="{$meta.image.height}">*}
            {*<meta name="twitter:card" content="{$meta.twitter.card}">*}
            {*<meta name="twitter:site" content="{$meta.twitter.site}">*}
        {/block}

        <link type="text/css" rel="stylesheet" href="css/application.css">
               
    </head>
    <body>

    {block "content"}

    {/block}



        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/application.js"></script>

        {*{literal}*}
            {*<script type="text/javascript">*}
                {*var _gaq = _gaq || [];*}
                {*_gaq.push(['_setAccount', '{$base.googleAnalyticsKey}']);*}
                {*_gaq.push(['_setDomainName', 'none']);*}
                {*_gaq.push(['_trackPageview']);*}
                {*(function() {*}
                    {*var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;*}
                    {*ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';*}
                    {*var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);*}
                {*})();*}
            {*</script>*}
        {*{/literal}*}

        {block "scripts"}

        {/block}


    </body>
</html>
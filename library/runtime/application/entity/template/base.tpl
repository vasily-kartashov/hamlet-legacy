<!DOCTYPE html>
<html class="no-js {$base.currentLanguage} {$base.textDirection}" prefix="og: http://ogp.me/ns#" dir="{$base.textDirection}" lang="{$base.currentLanguage}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{$base.siteTitle}</title>

        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//google-analytics.com">

        {*<link rel="home" title="{$base.siteTitle}" href="{$base.completePath}">*}
        {*<link rel="contents" title="{$sitemap.title}" href="{$sitemap.completePath}">*}
        {*<link rel="author" href="https://plus.google.com/{$base.officialGooglePlusId}">*}
        {block "links"}{/block}

        <meta name="description" content="{$base.meta.description}">
        <meta name="keywords" content="{$base.meta.keywords}">
        <meta name="viewport" content="width=1024">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        {*<meta name="fb:app_id" content="{$facebook.appId}">*}

        {block "opengraph"}
            {*<meta property="og:title" content="{$base.siteTitle}">*}
            {*<meta property="og:type" content="website">*}
            {*<meta property="og:description" content="{$meta.description}">*}
            {*<meta property="og:url" content="{$base.shareUrl}">*}
            {*<meta property="og:image" content="{$meta.image.src}">*}
            {*<meta property="og:image:width" content="{$meta.image.width}">*}
            {*<meta property="og:image:height" content="{$meta.image.height}">*}
            {*<meta name="twitter:card" content="{$meta.twitter.card}">*}
            {*<meta name="twitter:site" content="{$meta.twitter.site}">*}
        {/block}

        <!--[if lt IE 9]>
        <script src="/js/vendor/html5shiv.js"></script>
        <![endif]-->
        <link type="text/css" rel="stylesheet" href="css/main.css">

        <script type="text/javascript">
            document.documentElement.className = document.documentElement.className.replace('no-js','');
        </script>
               
    </head>
    <body class="{$base.body.className}" data-class="{$base.body.dataClass}">
        <div id="fb-root"></div>

    {block "content"}

    {/block}


    {block "scripts"}

        <script type="text/javascript">

            window.__environment = {$base.jsEnvironment|json};

            {literal}
            (function(d){
                var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
                js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/es_LA/all.js";
                d.getElementsByTagName('head')[0].appendChild(js);
            }(document));
            {/literal}

        </script>

        {typescript fileSystemPath="../../../../../public/js" urlPrefix="/js" isDevelopmentMode=!$base.concatenateJs}


    {/block}


    </body>
</html>
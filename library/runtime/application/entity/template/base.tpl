<!DOCTYPE html>
<html class="no-js {$base.currentLanguage} {$base.textDirection}" prefix="og: http://ogp.me/ns#" dir="{$base.textDirection}" lang="{$base.currentLanguage}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{$base.siteTitle}</title>
        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//google-analytics.com">
        {block "links"}{/block}
        <meta name="description" content="{$base.meta.description}">
        <meta name="keywords" content="{$base.meta.keywords}">
        <meta name="viewport" content="width=1024">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!--[if lt IE 9]>
        <script src="/js/vendor/html5shiv.js"></script>
        <![endif]-->
        <link type="text/css" rel="stylesheet" href="css/main.css">
        <script type="text/javascript">
            document.documentElement.className = document.documentElement.className.replace('no-js','');
        </script>
    </head>
    <body class="{$base.body.className}" data-bean="pageView:HomePage(todoList,textBox)">
        <div id="fb-root"></div>
        {block "content"}{/block}
        {block "scripts"}
            <script type="text/javascript">
                window.__environment = {$base.jsEnvironment|json};
            </script>
            {typescript fileSystemPath="../../../../../public/js" urlPrefix="/js" isDevelopmentMode=!$base.concatenateJs}
        {/block}
    </body>
</html>
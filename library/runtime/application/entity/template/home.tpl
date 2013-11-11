<html>
    <head>
        <link rel="stylesheet" href="/css/application.css"/>
    </head>
    <body data-class="Views.Document(todoList,textBox)">
        <h1>{$greeting}</h1>
        <ul data-ref="todoList" data-class="Views.TodoList(this)"></ul>
        Add newtem1234: <input data-ref="textBox" data-class="Views.TextBox(this)" />

        {typescript fileSystemPath="../../../../../public/js" urlPrefix="/js" isDevelopmentMode=true}

        <div id="fb-root"></div>
        <script>
            {literal}
            (function(d){
                var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
                    js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/es_LA/all.js";
                d.getElementsByTagName('head')[0].appendChild(js);
            }(document));
            {/literal}
        </script>
    </body>
</html>

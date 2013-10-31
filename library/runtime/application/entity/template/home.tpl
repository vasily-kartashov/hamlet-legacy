<html>
    <head>
        <link rel="stylesheet" href="/css/application.css"/>
    </head>
    <body data-class="Views.Document(todoList,textBox)">
        <h1>{$greeting}</h1>
        <ul data-ref="todoList" data-class="Views.TodoList(this)"></ul>
        Add new item: <input data-ref="textBox" data-class="Views.TextBox(this)" />
        <script src="/js/jquery-2.0.3.min.js"></script>
        <script src="/js/Main.js"></script>
    </body>
</html>

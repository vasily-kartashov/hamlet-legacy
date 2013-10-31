<html>
    <head>
        <link rel="stylesheet" href="/css/application.css"/>
    </head>
    <body data-class="DocumentView(textBox,label,todoList)">
        <h1 data-ref="label" data-class="LabelView(this)">{$greeting}</h1>
        <ul data-ref="todoList" data-class="TodoListView(this)"></ul>
        Add new item: <input data-ref="textBox" data-class="TextBoxView(this)" />
        <script src="/js/jquery-2.0.3.min.js"></script>
        <script src="/js/Main.js"></script>
    </body>
</html>

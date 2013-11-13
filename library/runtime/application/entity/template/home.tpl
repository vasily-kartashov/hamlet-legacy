{extends file="./base.tpl"}
{block "content"}
    <h1>{$content.greeting}</h1>
    <ul data-ref="todoList" data-class="Views.TodoList(this)"></ul>
    Add new item: <input data-ref="textBox" data-class="Views.TextBox(this)" />

{/block}
    <body data-class="Views.Document(todoList,textBox)">

    </body>


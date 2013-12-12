{extends file="./base.tpl"}
{block "content"}
    <h1>{$content.greeting}</h1>
    <ul data-bean="todoList:Views.TodoList(this)"></ul>
    <span>Add new item:</span>
    <input data-ref="textBox" data-bean="textBox:Views.TextBox(this)" />
{/block}


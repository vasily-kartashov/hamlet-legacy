{extends file="./base.tpl"}
{block "content"}
    <h1>{$content.greeting}</h1>
    <ul data-ref="todoList" data-class="Views.TodoList(this)"></ul>
    <span>Add new item:</span><input data-ref="textBox" data-class="Views.TextBox(this,d)" />
{/block}


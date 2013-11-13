/**
 * Created by danny on 13/11/2013.
 */

///<reference path="BasePage"/>
/// <reference path="Views"/>

class HomePage extends BasePage {

    constructor(private todoList: Views.TodoList, private textBox: Views.TextBox) {
        super();
    }

    public init(facebookAccessToken: string) {
        console.log(facebookAccessToken);
        var service = new Service.Endpoint(facebookAccessToken);
        this.todoList.init(service);
        this.textBox.onEnter((content: string) => this.todoList.addItem(content));
    }

}

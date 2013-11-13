/**
* Created by danny on 13/11/2013.
*/
var __extends = this.__extends || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
///<reference path="BasePage"/>
/// <reference path="Views"/>
var HomePage = (function (_super) {
    __extends(HomePage, _super);
    function HomePage(todoList, textBox) {
        _super.call(this);
        this.todoList = todoList;
        this.textBox = textBox;
    }
    HomePage.prototype.init = function (facebookAccessToken) {
        var _this = this;
        console.log(facebookAccessToken);
        var service = new Service.Endpoint(facebookAccessToken);
        this.todoList.init(service);
        this.textBox.onEnter(function (content) {
            return _this.todoList.addItem(content);
        });
    };
    return HomePage;
})(BasePage);
//# sourceMappingURL=HomePage.js.map

/**
* Created by danny on 13/11/2013.
*/
/// <reference path="vendor/jquery-2.0.3.d"/>
/// <reference path="Service"/>
/// <reference path="Assert"/>
var Views;
(function (Views) {
    var Document = (function () {
        function Document(todoList, textBox) {
            this.todoList = todoList;
            this.textBox = textBox;
        }
        Document.prototype.init = function (accessToken) {
            var _this = this;
            console.log(accessToken);
            var service = new Service.Endpoint(accessToken);
            this.todoList.init(service);
            this.textBox.onEnter(function (content) {
                _this.todoList.addItem(content);
            });
        };
        return Document;
    })();
    Views.Document = Document;
    var TodoList = (function () {
        function TodoList(el) {
            this.el = el;
        }
        TodoList.prototype.init = function (service) {
            var _this = this;
            this.service = service;
            service.getItems(function (items) {
                _this._addItems(items);
            });
            $(this.el).on('click', 'li', function (event) {
                var target = $(event.target);
                _this.service.updateStatus(target.data('id'), target.hasClass('done'), function () {
                    target.toggleClass('done');
                });
            });
        };
        TodoList.prototype.assert = function () {
            Assert.hasData($(this.el).find('li'), 'id');
        };
        TodoList.prototype.addItem = function (content) {
            var _this = this;
            this.service.addItem(content, function (item) {
                _this._addItem(item);
            });
        };
        TodoList.prototype._addItem = function (item) {
            var li = $('<li/>').text(item.content).toggleClass('done', item.done).data('id', item.id);
            $(this.el).append(li);
        };
        TodoList.prototype._addItems = function (items) {
            for (var i = 0, l = items.length; i < l; i++) {
                this._addItem(items[i]);
            }
        };
        return TodoList;
    })();
    Views.TodoList = TodoList;
    var TextBox = (function () {
        function TextBox(el) {
            this.el = el;
        }
        TextBox.prototype.onEnter = function (callback) {
            $(this.el).keyup(function (event) {
                if (event.which == 13) {
                    callback($(this).val());
                    $(this).val('');
                }
            });
        };
        return TextBox;
    })();
    Views.TextBox = TextBox;
})(Views || (Views = {}));
//# sourceMappingURL=Views.js.map

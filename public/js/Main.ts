/// <reference path="facebook.d"/>
/// <reference path="Builder"/>
/// <reference path="jquery-2.0.3.d"/>

module Views {
    export class Document {
        private facebook: Facebook.Facebook;
        constructor(todoList: TodoList, textBox: TextBox) {
            textBox.onEnter(todoList, todoList.addItem);
        }
        public setFacebook(facebook: Facebook.Facebook) {
            this.facebook = facebook;
        }
    }
    export class TodoList {
        private service: Service.Endpoint;
        constructor(private el: HTMLUListElement) {
            this.service = new Service.Endpoint();
            var service = this.service;
            service.getItems(this, this._addItems);
            $(el).on('click', 'li', function(event: Event) {
                var target = $(event.target);
                service.updateStatus(target.data('id'), target.hasClass('done'), target, function() {
                    this.toggleClass('done');
                });
            });
        }
        public addItem(content: string) {
            this.service.addItem(content, this, this._addItem);
        }
        private _addItem(item: Service.Item) {
            var li = $('<li/>').text(item.content).toggleClass('done', item.done).data('id', item.id);
            $(this.el).append(li);
        }
        private _addItems(items: Service.Item[]) {
            for (var i = 0, l = items.length; i < l; i++) {
                this._addItem(items[i]);
            }
        }
    }
    export class TextBox {
        constructor(private el: HTMLInputElement) {}
        public onEnter(scope: any, callback: (value: string) => void) {
            $(this.el).keyup(function(event) {
                if (event.which == 13) {
                    callback.call(scope, $(this).val());
                    $(this).val('');
                }
            });
        }
    }
}

module Service {
    export interface Item {
        id: number;
        content: string;
        done: boolean;
    }
    export class Endpoint {
        constructor() {}
        public getItems(scope: any, callback: (items: Item[]) => void) {
            $.ajax({
                url: '/items',
                method: 'GET',
                success: function(items: Item[]) {
                    callback.call(scope, items);
                }
            });
        }
        public addItem(content: string, scope: any, callback: (item: Item) => void) {
            $.ajax({
                url: '/items',
                method: 'PUT',
                data: {
                    content: content
                },
                success: function(item: Item) {
                    callback.call(scope, item);
                }
            });
        }
        public updateStatus(id: number, done: boolean, scope: any, callback: () => void) {
            $.ajax({
                url: '/items/' + id.toString() + '/' + (done? 'undo' : 'do'),
                method: 'POST',
                success: function() {
                    callback.call(scope);
                }
            })
        }
    }
}

declare var window: FacebookWindow;

$(document).ready(function() {
    window.fbAsyncInit = function() {
        FB.init({
            appId: '543290215752753',
            status: true,
            cookie: true
        });
        console.time("Initializing Application");
        var documentView = <Views.Document> Builder.init(document);
        console.timeEnd("Initializing Application");
        documentView.setFacebook(FB);
        console.log(documentView);
    }
});



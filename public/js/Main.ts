/// <reference path="facebook.d"/>
/// <reference path="Builder"/>
/// <reference path="jquery-2.0.3.d"/>
/// <reference path="Assert"/>

module Views {
    export class Document {
        constructor(private todoList: TodoList, private textBox: TextBox) {}
        public init(accessToken: string) {
            console.log(accessToken);
            var service = new Service.Endpoint(accessToken);
            this.todoList.init(service);
            this.textBox.onEnter((content: string) => {
                this.todoList.addItem(content);
            });
        }
    }
    export class TodoList {
        private service: Service.Endpoint;
        constructor(private el: HTMLUListElement) {}
        public init(service: Service.Endpoint) {
            this.service = service;
            service.getItems((items: Service.Item[]) => {
                this._addItems(items);
            });
            $(this.el).on('click', 'li', (event: Event) => {
                var target = $(event.target);
                this.service.updateStatus(target.data('id'), target.hasClass('done'), function() {
                    target.toggleClass('done');
                });
            });
        }
        public assert() {
            Assert.hasData($(this.el).find('li'), 'id');
        }
        public addItem(content: string) {
            this.service.addItem(content, (item: Service.Item) => {
                this._addItem(item);
            });
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
        public onEnter(callback: (value: string) => void) {
            $(this.el).keyup(function(event) {
                if (event.which == 13) {
                    callback($(this).val());
                    $(this).val('');
                }
            });
        }
    }
}

module Service {
    export interface Item {
        id: number;
        uid: number;
        content: string;
        done: boolean;
    }
    export class Endpoint {
        private headers;
        constructor(accessToken: string) {
            this.headers = {
                Authorization: 'Bearer ' + accessToken
            };
        }
        public getItems(callback: (items: Item[]) => void) {
            $.ajax({
                url: '/items',
                method: 'GET',
                success: function(items: Item[]) {
                    callback(items);
                },
                headers: this.headers
            });
        }
        public addItem(content: string, callback: (item: Item) => void) {
            $.ajax({
                url: '/items',
                method: 'PUT',
                data: {
                    content: content
                },
                success: function(item: Item) {
                    callback(item);
                },
                headers: this.headers
            });
        }
        public updateStatus(id: number, done: boolean, callback: () => void) {
            $.ajax({
                url: '/items/' + id.toString() + '/' + (done? 'undo' : 'do'),
                method: 'POST',
                success: function() {
                    callback();
                },
                headers: this.headers
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
        FB.login(function(response) {
            if (response.authResponse) {
                var documentView = <Views.Document> Builder.init(document, true);
                documentView.init(response.authResponse.accessToken);
            } else {
                console.log('not authorized');
            }
        });
    }
});



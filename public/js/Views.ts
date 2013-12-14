/// <reference path="vendor/jquery-2.0.3.d"/>
/// <reference path="Service"/>
/// <reference path="Assert"/>

module Views {
    export class Document {
        constructor(private todoList: TodoList, private textBox: TextBox) {}
        public init(accessToken: string) {
            console.log(accessToken);
            var service = new Service.Endpoint(accessToken);
            this.todoList.init(service);
            this.textBox.onEnter((content: string) => this.todoList.addItem(content));

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
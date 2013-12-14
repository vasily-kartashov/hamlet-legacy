///<reference path="vendor/jquery-2.0.3.d"/>

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


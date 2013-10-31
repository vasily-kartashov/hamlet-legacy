/// <reference path="jquery.d.ts" />

module Views {
    export class Document {
        constructor(todoList: TodoList, textBox: TextBox) {
            textBox.onEnter(todoList, todoList.addItem);
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

$(document).ready(function() {
    var documentView = <Views.Document> Application.init(this);
    console.log(documentView);
});

module Application {
    class Scope {
        private className: string;
        private names: {
            [name: string]: any;
        } = {};
        private namesOrder: string[] = [];
        private object: any = null;
        public init(): any {
            if (this.object == null) {
                if (!this.className) {
                    return this.object = this.element;
                } else {
                    var arguments = [];
                    for (var i = 0, l = this.namesOrder.length; i < l; i++) {
                        var name = this.namesOrder[i];
                        var argument: any;
                        if (name.substr(-2, 2) == '[]') {
                            argument = [];
                            for (var j = 0, m = this.names[name].length; j < m; j++) {
                                argument.push((<Scope> this.names[name][j]).init());
                            }
                        } else {
                            if (this.names[name] == null) {
                                throw "Name " + name + " is not initialized in the scope";
                            }
                            argument = (<Scope> this.names[name]).init();
                        }
                        arguments.push(argument);
                    }
                    var currentNamespace: any = window;
                    var namespaces = this.className.split('.');
                    for (var i = 0, l = namespaces.length; i < l; i++) {
                        if (namespaces[i] in currentNamespace) {
                            currentNamespace = currentNamespace[namespaces[i]];
                        } else {
                            throw "Namespace '" + namespaces[i] + "' not defined in '" + this.className + "'";
                        }
                    }
                    this.object = Object.create(currentNamespace.prototype);
                    this.object.constructor.apply(this.object, arguments);
                }
            }
            return this.object;
        }
        public addName(name: string, scope: Scope) {
            if (!(name in this.names)) {
                if (this.parent) {
                    this.parent.addName(name, scope);
                } else {
                    throw "Cannot bind " + name;
                }
            } else {
                if (name.substr(-2, 2) == '[]') {
                    this.names[name].push(scope);
                } else {
                    this.names[name] = scope;
                }
            }
        }
        constructor(private element: HTMLElement, initCode: string, ref: string, private parent: Scope) {
            if (ref != '' && parent != null) {
                parent.addName(ref, this);
            }
            if (initCode) {
                var position = initCode.indexOf('(');
                this.className = initCode.substr(0, position);
                var suffix = initCode.substr(position + 1).trim();
                var names = suffix.substring(0, suffix.length - 1).split(',');
                for (var i = 0, l = names.length; i < l; i++) {
                    var name = names[i];
                    if (name.substr(-2, 2) == '[]') {
                        this.names[name] = [];
                    } else {
                        this.names[name] = null;
                    }
                    this.namesOrder.push(name);
                }
            }
            if ('this' in this.names) {
                this.names['this'] = new Scope(this.element, '', '', this);
            }
        }
    }
    export function init(document: HTMLDocument): any {
        var process = function(node: Node, scope: Scope = null): Scope {
            if (node.nodeType != 1) {
                return scope;
            }
            var element = <HTMLElement> node;
            if (element.hasAttribute('data-ref') || element.hasAttribute('data-class')) {
                var ref = (element.getAttribute('data-class') || '').replace(/\s+/g, '');
                var initCode = (element.getAttribute('data-ref') || '').replace(/\s+/g, '');
                scope = new Scope(element, ref, initCode, scope);
            }
            var childNodes: NodeList = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), scope);
            }
            return scope;
        };
        return process(document.body).init();
    }
}

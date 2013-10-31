/// <reference path="jquery.d.ts" />

class DocumentView {
    constructor(private textBox: TextBoxView, private label: LabelView, private todoList: TodoListView) {
        this.textBox.onEnter(this.todoList, this.todoList.addItem);
    }
    getLabel(): LabelView {
        return this.label;
    }
}

class LabelView {
    constructor(private el: HTMLHeadingElement) {}
    public setValue(value: string) {
        $(this.el).text(value);
    }
}

class TodoListView {
    constructor(private el: HTMLUListElement, private items: HTMLLIElement[]) {}
    public addItem(value: string) {
        $(this.el).append($('<li/>').text(value));
    }
}

class TextBoxView {
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

$(document).ready(function() {
    var documentView = <DocumentView> ScopeBuilder.create(document);
    documentView.getLabel().setValue("Hey there");
    console.log(documentView);
});





















module ScopeBuilder {
    export class Scope {
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
                            var scopes = this.names[name];
                            argument = [];
                            for (var j = 0, m = this.names[name].length; j < m; j++) {
                                argument.push((<Scope> this.names[name][j]).init());
                            }
                        } else {
                            argument = (<Scope> this.names[name]).init();
                        }
                        arguments.push(argument);
                    }
                    this.object = Object.create(window[this.className].prototype);
                    this.object.constructor.apply(this.object, arguments);
                }
            }
            return this.object;
        }
        public addName(name: string, scope: Scope) {
            var n = name.substr(0, name.length - 2);
            if (!(name in this.names)) {
                if (this.parent) {
                    this.parent.addName(name, scope);
                } else {
                    new Error("cannot bind " + name);
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
            if (ref != undefined && parent != undefined) {
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
    export function create(document: HTMLDocument): any {
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
                process(<HTMLElement> childNodes.item(i), scope);
            }
            return scope;
        }
        return process(document.body).init();
    }
}



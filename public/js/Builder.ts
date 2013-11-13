module Builder {
    class Scope {
        private className: string;
        private names: {
            [name: string]: any;
        };
        private namesOrder: string[];
        private object: any;
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
                                throw new Error('Constructor argument ' + name + ' for class ' + this.className + ' has not been defined in the scope');
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
                            throw new Error('Class ' + this.className + ' is undefined') ;
                        }
                    }
                    this.object = Object.create(currentNamespace.prototype);
                    this.object.constructor.apply(this.object, arguments);
                }
            }
            return this.object;
        }
        public checkAssertions() {
            if (typeof this.object.assert === "function") {
                (<{assert: () => void}>this.object).assert();
            }
            for (var name in this.names) {
                if (this.names.hasOwnProperty(name)) {
                    this.names[name].checkAssertions();
                }
            }
        }
        public addName(name: string, scope: Scope) {
            if (!(name in this.names)) {
                if (this.parent) {
                    this.parent.addName(name, scope);
                } else {
                    throw Error('Ref ' + name + ' cannot be bound. ( No parent view defines "' + name + '" in its constructor )');
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
            this.names = {};
            this.namesOrder = [];
            this.object = null;
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
    export function init(document: Document, checkAssertions: boolean = false): any {
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
        var scope: Scope = process(document.body);
        if (checkAssertions) {
            setInterval(() => {
                scope.checkAssertions();
            }, 1000);
        }
        return scope.init();
    }
}
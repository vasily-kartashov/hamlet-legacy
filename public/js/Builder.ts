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

                    this.object = Object.create(this.getObjectConstructor().prototype);
                    this.object.constructor.apply(this.object, arguments);
                }
            }
            return this.object;
        }
        private getObjectConstructor()
        {
            var currentNamespace: any = window;
            var namespaces = this.className.split('.');
            for (var i = 0, l = namespaces.length; i < l; i++) {
                if (namespaces[i] in currentNamespace) {
                    currentNamespace = currentNamespace[namespaces[i]];
                } else {
                    throw new Error('Class ' + this.className + ' is undefined') ;
                }
            }

            return currentNamespace;
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

        public objectConstructorIsValid() : boolean {
            var argNamesArray = this.getObjectClassConstructorArgumentsArray();
            if(argNamesArray.length !== this.namesOrder.length){
                return false;
            } else {
                for(var i = 0; i< argNamesArray.length;i++){
                    if(this.namesOrder[i] !== 'this' && argNamesArray[i] !== this.namesOrder[i]){
                        return false;
                    }
                }
            }
            return true;
        }
        public getObjectClassConstructorArgumentsArray() : string[] {
            // @todo, the checking this is an object wrappre is a bit messy
            if(this.isObjectWrapper()){
                var argsStr = this.getObjectConstructor().toString().match(/\((.*?)\)/)[1].replace(/\s/g,'');
                if(argsStr === ''){
                    return [];
                }
                return argsStr.split(',');
            } else {
                return null;
            }
        }
        public addName(name: string, scope: Scope) {
            if (!(name in this.names)) {
                if (this.parent) {
                    this.parent.addName(name, scope);
                } else {
                    throw new Error('Ref ' + name + ' cannot be bound. ( No parent view defines "' + name + '" in its constructor )');
                }
            } else {
                if (name.substr(-2, 2) == '[]') {
                    this.names[name].push(scope);
                } else {
                    this.names[name] = scope;
                }
            }
        }
        public getClassName(){
            return this.className;
        }
        public isObjectWrapper() : boolean {
            return this.className && this.className !== '';
        }
        constructor(private element: HTMLElement, initCode: string, ref: string, private parent: Scope) {
            this.names = {};
            this.namesOrder = [];
            this.object = null;
            if (parent != null) {
                if (!ref) {
                    throw new Error('Class ' + initCode + ' must have a ref');
                } else {
                    parent.addName(ref, this);
                }
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
                this.names['this'] = new Scope(this.element, '', 'this', this);
            }
        }
    }
    export function init(document: Document, developmentMode: boolean = false): any {
        var process = function(node: Node, scope: Scope = null): Scope {
            if (node.nodeType != 1) {
                return scope;
            }
            var element = <HTMLElement> node;
            if (element.tagName.toLowerCase() === 'iframe') {
                return scope;
            }
            if (element.hasAttribute('data-ref') || element.hasAttribute('data-class')) {
                var initCode = (element.getAttribute('data-class') || '').replace(/\s+/g, '');
                var ref = (element.getAttribute('data-ref') || '').replace(/\s+/g, '');
                scope = new Scope(element, initCode, ref, scope);
                if(developmentMode){
                    if(scope.isObjectWrapper() && !scope.objectConstructorIsValid()){
                        throw new Error('Class constructor in DOM ' + initCode + ' does not match javascript definition ' + scope.getClassName() + '(' +scope.getObjectClassConstructorArgumentsArray().join(',') +')');
                    }
                }
            }
            var childNodes: NodeList = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), scope);
            }
            return scope;
        };
        var scope: Scope = process(document.body);
        if (developmentMode) {
            setInterval(() => {
                scope.checkAssertions();
            }, 1000);
        }
        return scope.init();
    }
}
var Builder;
(function (Builder) {
    var Scope = (function () {
        function Scope(element, initCode, ref, parent) {
            this.element = element;
            this.parent = parent;
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
        Scope.prototype.init = function () {
            if (this.object == null) {
                if (!this.className) {
                    return this.object = this.element;
                } else {
                    var arguments = [];
                    for (var i = 0, l = this.namesOrder.length; i < l; i++) {
                        var name = this.namesOrder[i];
                        var argument;
                        if (name.substr(-2, 2) == '[]') {
                            argument = [];
                            for (var j = 0, m = this.names[name].length; j < m; j++) {
                                argument.push((this.names[name][j]).init());
                            }
                        } else {
                            if (this.names[name] == null) {
                                throw new Error('Constructor argument ' + name + ' for class ' + this.className + ' has not been defined in the scope');
                            }
                            argument = (this.names[name]).init();
                        }
                        arguments.push(argument);
                    }

                    this.object = Object.create(this.getObjectConstructor().prototype);
                    this.object.constructor.apply(this.object, arguments);
                }
            }
            return this.object;
        };
        Scope.prototype.getObjectConstructor = function () {
            var currentNamespace = window;
            var namespaces = this.className.split('.');
            for (var i = 0, l = namespaces.length; i < l; i++) {
                if (namespaces[i] in currentNamespace) {
                    currentNamespace = currentNamespace[namespaces[i]];
                } else {
                    throw new Error('Class ' + this.className + ' is undefined');
                }
            }

            return currentNamespace;
        };

        Scope.prototype.checkAssertions = function () {
            if (typeof this.object.assert === "function") {
                (this.object).assert();
            }
            for (var name in this.names) {
                if (this.names.hasOwnProperty(name)) {
                    this.names[name].checkAssertions();
                }
            }
        };

        Scope.prototype.objectConstructorIsValid = function () {
            var argNamesArray = this.getObjectClassConstructorArgumentsArray();
            if (argNamesArray.length !== this.namesOrder.length) {
                return false;
            } else {
                for (var i = 0; i < argNamesArray.length; i++) {
                    if (this.namesOrder[i] !== 'this' && argNamesArray[i] !== this.namesOrder[i]) {
                        return false;
                    }
                }
            }
            return true;
        };
        Scope.prototype.getObjectClassConstructorArgumentsArray = function () {
            if (this.isObjectWrapper()) {
                var argsStr = this.getObjectConstructor().toString().match(/\((.*?)\)/)[1].replace(/\s/g, '');
                if (argsStr === '') {
                    return [];
                }
                return argsStr.split(',');
            } else {
                return null;
            }
        };
        Scope.prototype.addName = function (name, scope) {
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
        };
        Scope.prototype.getClassName = function () {
            return this.className;
        };
        Scope.prototype.isObjectWrapper = function () {
            return this.className && this.className !== '';
        };
        return Scope;
    })();
    function init(document, developmentMode) {
        if (typeof developmentMode === "undefined") { developmentMode = false; }
        var process = function (node, scope) {
            if (typeof scope === "undefined") { scope = null; }
            if (node.nodeType != 1) {
                return scope;
            }
            var element = node;
            if (element.tagName.toLowerCase() === 'iframe') {
                return scope;
            }
            if (element.hasAttribute('data-ref') || element.hasAttribute('data-class')) {
                var initCode = (element.getAttribute('data-class') || '').replace(/\s+/g, '');
                var ref = (element.getAttribute('data-ref') || '').replace(/\s+/g, '');
                scope = new Scope(element, initCode, ref, scope);
                if (developmentMode) {
                    if (scope.isObjectWrapper() && !scope.objectConstructorIsValid()) {
                        throw new Error('Class constructor in DOM ' + initCode + ' does not match javascript definition ' + scope.getClassName() + '(' + scope.getObjectClassConstructorArgumentsArray().join(',') + ')');
                    }
                }
            }
            var childNodes = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), scope);
            }
            return scope;
        };
        var scope = process(document.body);
        if (developmentMode) {
            setInterval(function () {
                scope.checkAssertions();
            }, 1000);
        }
        return scope.init();
    }
    Builder.init = init;
})(Builder || (Builder = {}));
//# sourceMappingURL=Builder.js.map

var Builder;
(function (Builder) {
    var Scope = (function () {
        function Scope(element, initCode, ref, parent) {
            this.element = element;
            this.parent = parent;
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
                                throw "Name " + name + " is not initialized in the scope";
                            }
                            argument = (this.names[name]).init();
                        }
                        arguments.push(argument);
                    }
                    var currentNamespace = window;
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
        Scope.prototype.addName = function (name, scope) {
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
        };
        return Scope;
    })();
    function init(document, checkAssertions) {
        if (typeof checkAssertions === "undefined") { checkAssertions = false; }
        var process = function (node, scope) {
            if (typeof scope === "undefined") { scope = null; }
            if (node.nodeType != 1) {
                return scope;
            }
            var element = node;
            if (element.hasAttribute('data-ref') || element.hasAttribute('data-class')) {
                var ref = (element.getAttribute('data-class') || '').replace(/\s+/g, '');
                var initCode = (element.getAttribute('data-ref') || '').replace(/\s+/g, '');
                scope = new Scope(element, ref, initCode, scope);
            }
            var childNodes = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), scope);
            }
            return scope;
        };
        var scope = process(document.body);
        if (checkAssertions) {
            setInterval(function () {
                scope.checkAssertions();
            }, 1000);
        }
        return scope.init();
    }
    Builder.init = init;
})(Builder || (Builder = {}));
//# sourceMappingURL=Builder.js.map

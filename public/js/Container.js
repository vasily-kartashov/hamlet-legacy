/**
* Created by danny on 20/11/2013.
*/
var Container;
(function (Container) {
    var isDebugMode;

    function log(message) {
        if (isDebugMode) {
            console.log(message);
        }
    }

    function group(name) {
        if (isDebugMode) {
            console.group(name);
        }
    }

    function groupEnd() {
        if (isDebugMode) {
            console.groupEnd();
        }
    }

    var Slot = (function () {
        function Slot(name, isMultiple) {
            this.name = name;
            this.isMultiple = isMultiple;
        }
        Slot.prototype.getName = function () {
            return this.name;
        };
        Slot.prototype.isArray = function () {
            return this.isMultiple;
        };
        Slot.prototype.toString = function () {
            return this.name + (this.isMultiple ? "[]" : "");
        };
        return Slot;
    })();
    Container.Slot = Slot;

    var Scope = (function () {
        function Scope(slots, parentScope) {
            if (typeof slots === "undefined") { slots = []; }
            if (typeof parentScope === "undefined") { parentScope = null; }
            this.slots = slots;
            this.parentScope = parentScope;
            this.beans = {};
        }
        Scope.prototype.getBeans = function (name) {
            if (this.beans.hasOwnProperty(name)) {
                return this.beans[name];
            }
            throw new Error("The slot '" + name + "' is empty");
        };
        Scope.prototype.setBean = function (name, bean) {
            for (var i = 0, l = this.slots.length; i < l; i++) {
                var slot = this.slots[i];
                if (slot.getName() == name) {
                    log("Slot found for bean '" + name + "'");
                    if (!this.beans.hasOwnProperty(name)) {
                        this.beans[name] = [];
                    }
                    if (!slot.isArray() && this.beans[name].length > 0) {
                        throw new Error("The slot '" + name + "' is already filled");
                    }
                    this.beans[name].push(bean);
                    return;
                }
            }
            if (this.parentScope == null) {
                if (!this.beans.hasOwnProperty(name)) {
                    this.beans[name] = [];
                }
                log("Adding bean '" + name + "' to global scope");
                this.beans[name].push(bean);
            } else {
                log("Trying to attach bean '" + name + "' to parent scope");
                this.parentScope.setBean(name, bean);
            }
        };
        Scope.prototype.init = function () {
            for (var name in this.beans) {
                var beans = this.beans[name];
                for (var i = 0, l = beans.length; i < l; i++) {
                    beans[i].getInstance();
                }
            }
        };
        Scope.prototype.getFirstInstance = function (name) {
            if (this.beans.hasOwnProperty(name)) {
                for (var i in this.beans[name]) {
                    return this.beans[name][i].getInstance();
                }
            }
            throw new Error("The slot '" + name + "' is empty");
        };
        Scope.prototype.getNames = function () {
            var result = [];
            for (var name in this.beans) {
                result.push(name);
            }
            return result;
        };
        return Scope;
    })();
    Container.Scope = Scope;

    var Bean = (function () {
        function Bean(initialisationCode, element, parentScope) {
            this.element = element;
            this.parentScope = parentScope;
            group("Bean details");
            log("Initialisation code: " + initialisationCode);
            this.slots = [];
            var colonPosition = initialisationCode.indexOf(':');
            if (colonPosition == -1) {
                throw new Error("Wrong initilisation code '" + initialisationCode + "'");
            }
            this.name = initialisationCode.substr(0, colonPosition);
            log("Bean name: " + this.name);
            var leftBracketPosition = initialisationCode.indexOf('(');
            if (leftBracketPosition == -1) {
                // constructor without arguments
                this.qualifiedClassName = initialisationCode.substr(colonPosition + 1);
                log("Qualified class name: " + this.qualifiedClassName);
            } else {
                // constructor with arguments
                this.qualifiedClassName = initialisationCode.substr(colonPosition + 1, leftBracketPosition - colonPosition - 1);
                log("Qualified class name: " + this.qualifiedClassName);
                var argumentsCode = initialisationCode.substr(leftBracketPosition + 1, initialisationCode.length - leftBracketPosition - 2);
                if (argumentsCode.length > 0) {
                    var arguments = argumentsCode.split(',');
                    for (var i = 0, l = arguments.length; i < l; i++) {
                        var argument = arguments[i];
                        var slot;
                        if (argument.substr(-2, 2) == '[]') {
                            slot = new Slot(argument.substr(0, argument.length - 2), true);
                        } else {
                            slot = new Slot(argument, false);
                        }
                        log("Slot #" + i + ": " + slot.toString());
                        this.slots.push(slot);
                    }
                }
            }
            groupEnd();
            if (this.qualifiedClassName == 'this') {
                // don't wait too long if it's the current node we're wrapping here
                this.instance = this.element;
            }
            this.currentScope = new Scope(this.slots, parentScope);
            log("Attaching bean");
            this.parentScope.setBean(this.name, this);
            log("Bean constructed");
            log(this);
        }
        Bean.prototype.getName = function () {
            return this.name;
        };
        Bean.prototype.getInstance = function () {
            if (this.instance == null) {
                var arguments = [];
                for (var i = 0, l = this.slots.length; i < l; i++) {
                    var slot = this.slots[i];
                    if (slot.getName() == 'this') {
                        arguments.push(this.element);
                    } else if (slot.isArray()) {
                        var instances = [];
                        var beans = this.currentScope.getBeans(slot.getName());
                        for (var j = 0, m = beans.length; j < m; j++) {
                            instances.push(beans[j].getInstance());
                        }
                        arguments.push(instances);
                    } else {
                        arguments.push(this.currentScope.getBeans(slot.getName())[0].getInstance());
                    }
                }
                var currentNamespace = window;
                var namespaces = this.qualifiedClassName.split('.');
                for (var i = 0, l = namespaces.length; i < l; i++) {
                    if (namespaces[i] in currentNamespace) {
                        currentNamespace = currentNamespace[namespaces[i]];
                    } else {
                        throw new Error("Class '" + this.qualifiedClassName + "' is undefined");
                    }
                }
                this.instance = Object.create(currentNamespace.prototype);
                this.instance.constructor.apply(this.instance, arguments);
            }
            return this.instance;
        };
        Bean.prototype.getScope = function () {
            return this.currentScope;
        };
        return Bean;
    })();
    Container.Bean = Bean;

    function load(rootElement, debug) {
        if (typeof debug === "undefined") { debug = false; }
        isDebugMode = debug;
        var process = function (node, scope) {
            if (typeof scope === "undefined") { scope = null; }
            if (node.nodeType != 1) {
                return scope;
            }
            var element = node;
            var newScope;
            if (element.hasAttribute('data-bean')) {
                group("Processing Element");
                log(element);
                var code = element.getAttribute('data-bean').replace(/\s+/g, '');
                var bean = new Bean(code, element, scope);
                newScope = bean.getScope();
                groupEnd();
            } else {
                newScope = scope;
            }
            var childNodes = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), newScope);
            }
            return scope;
        };
        var globalScope = process(rootElement, new Scope());
        log("Names in global scope: '" + globalScope.getNames().join("', '") + "'");
        globalScope.init();
        return globalScope;
    }
    Container.load = load;
})(Container || (Container = {}));
//# sourceMappingURL=Container.js.map

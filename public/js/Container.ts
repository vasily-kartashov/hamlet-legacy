/**
 * Created by danny on 20/11/2013.
 */
module Container {

    var isDebugMode;

    function log(message: any) {
        if (isDebugMode) {
            console.log(message);
        }
    }

    function group(name: string) {
        if (isDebugMode) {
            console.group(name);
        }
    }

    function groupEnd() {
        if (isDebugMode) {
            console.groupEnd();
        }
    }

    export class Slot {
        constructor(private name: string, private isMultiple: boolean) { }
        public getName(): string {
            return this.name;
        }
        public isArray(): boolean {
            return this.isMultiple;
        }
        public toString(): string {
            return this.name + (this.isMultiple ? "[]" : "");
        }
    }

    export class Scope {
        private beans: {
            [name: string]: Bean[];
        } = {};

        constructor(private slots: Slot[] =[], private parentScope: Scope = null) {}

        public getBeans(name: string): Bean[] {
            if (this.beans.hasOwnProperty(name)) {
                return this.beans[name];
            }
            throw new Error("The slot '" + name + "' is empty");
        }
        public setBean(name: string, bean: Bean) {
            for (var i = 0, l = this.slots.length; i < l; i++) {
                var slot = <Slot>this.slots[i];
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
                // this is global scope so just add bean to the scope
                if (!this.beans.hasOwnProperty(name)) {
                    this.beans[name] = [];
                }
                log("Adding bean '" + name + "' to global scope");
                this.beans[name].push(bean);
            } else {
                log("Trying to attach bean '" + name + "' to parent scope");
                this.parentScope.setBean(name, bean);
            }
        }
        public init() {
            for (var name in this.beans) {
                var beans: Bean[] = this.beans[name];
                for (var i = 0, l = beans.length; i < l; i++) {
                    beans[i].getInstance();
                }
            }
        }
        public getFirstInstance(name: string): any {

            if (this.beans.hasOwnProperty(name)) {
                for (var i in this.beans[name]) {
                    return this.beans[name][i].getInstance();
                }
            }
            throw new Error("The slot '" + name + "' is empty");
        }
        public getNames(): string[]{
            var result: string[] = [];
            for (var name in this.beans) {
                result.push(name);
            }
            return result;
        }
    }

    export class Bean {
        private name: string;
        private qualifiedClassName: string;
        private slots: Slot[];
        private instance: any;
        private currentScope: Scope;
        constructor(initialisationCode: string, private element: HTMLElement, private parentScope: Scope) {
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
                        var slot: Slot;
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
        public getName(): string {
            return this.name;
        }
        public getInstance(): any {
            if (this.instance == null) {
                var arguments = [];
                for (var i = 0, l = this.slots.length; i < l; i++) {
                    var slot: Slot = this.slots[i];
                    if (slot.getName() == 'this') {
                        arguments.push(this.element);
                    } else if (slot.isArray()) {
                        var instances = [];
                        var beans: Bean[] = this.currentScope.getBeans(slot.getName());
                        for (var j = 0, m = beans.length; j < m; j++) {
                            instances.push(beans[j].getInstance());
                        }
                        arguments.push(instances);
                    } else {
                        arguments.push(this.currentScope.getBeans(slot.getName())[0].getInstance());
                    }
                }
                var currentNamespace: any = window;
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
        }
        public getScope(): Scope {
            return this.currentScope;
        }
    }

    export function load(rootElement: HTMLElement, debug: boolean = false): Scope {
        isDebugMode = debug;
        var process = function (node: Node, scope: Scope = null): Scope {
            if (node.nodeType != 1) {
                return scope;
            }
            var element = <HTMLElement> node;
            var newScope: Scope;
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
            var childNodes: NodeList = element.childNodes;
            for (var i = 0, l = childNodes.length; i < l; i++) {
                process(childNodes.item(i), newScope);
            }
            return scope;
        };
        var globalScope: Scope = process(rootElement, new Scope());
        log("Names in global scope: '" + globalScope.getNames().join("', '") + "'");
        globalScope.init();
        return globalScope;
    }
}

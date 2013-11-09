/// <reference path="jquery-2.0.3.d"/>

module Assert {
    export function exists(el: HTMLElement, message: string = '') {
        if ($(el).length != 1) {
            throw message || "Assertion failed";
        }
    }
    export function hasData(el: HTMLElement, name: string, message: string = '') {
        if ($(el).data(name) == undefined) {
            throw message || "Assertion failed";
        }
    }
}
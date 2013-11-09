/// <reference path="jquery-2.0.3.d"/>

module Assert {
    export function hasData($el: JQuery, name: string, message: string = '') {
        $el.each(function(item) {
            if ($(item).data(name) == undefined) {
                throw message || "Assertion failed";
            }
        });
    }
}
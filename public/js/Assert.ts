/// <reference path="vendor/jquery-2.0.3.d"/>

module Assert {
    export function hasData($el: JQuery, name: string, message: string = '') {
        $el.each(function(index, item) {
            if (!$(item).data(name)) {
                throw message || "Data field '" + name + "' is not defined for object " + item.toString();
            }
        });
    }
}



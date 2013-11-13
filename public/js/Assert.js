/// <reference path="vendor/jquery-2.0.3.d"/>
var Assert;
(function (Assert) {
    function hasData($el, name, message) {
        if (typeof message === "undefined") { message = ''; }
        $el.each(function (index, item) {
            if (!$(item).data(name)) {
                throw message || "Data field '" + name + "' is not defined for object " + item.toString();
            }
        });
    }
    Assert.hasData = hasData;
})(Assert || (Assert = {}));
//# sourceMappingURL=Assert.js.map

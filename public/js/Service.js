///<reference path="vendor/jquery-2.0.3.d"/>
var Service;
(function (Service) {
    var Endpoint = (function () {
        function Endpoint(accessToken) {
            this.headers = {
                Authorization: 'Bearer ' + accessToken
            };
        }
        Endpoint.prototype.getItems = function (callback) {
            $.ajax({
                url: '/items',
                method: 'GET',
                success: function (items) {
                    callback(items);
                },
                headers: this.headers
            });
        };
        Endpoint.prototype.addItem = function (content, callback) {
            $.ajax({
                url: '/items',
                method: 'PUT',
                data: {
                    content: content
                },
                success: function (item) {
                    callback(item);
                },
                headers: this.headers
            });
        };
        Endpoint.prototype.updateStatus = function (id, done, callback) {
            $.ajax({
                url: '/items/' + id.toString() + '/' + (done ? 'undo' : 'do'),
                method: 'POST',
                success: function () {
                    callback();
                },
                headers: this.headers
            });
        };
        return Endpoint;
    })();
    Service.Endpoint = Endpoint;
})(Service || (Service = {}));
//# sourceMappingURL=Service.js.map

/**
* Created by danny on 13/11/2013.
*/
var Environment = (function () {
    function Environment(json) {
        this.facebookAppId = json.facebookAppId;
    }
    Environment.prototype.getFacebookAppId = function () {
        return this.facebookAppId;
    };
    return Environment;
})();
//# sourceMappingURL=Environment.js.map

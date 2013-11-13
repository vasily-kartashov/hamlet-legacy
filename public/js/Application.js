/**
* Created by danny on 13/11/2013.
*/
///<reference path="Environment"/>
///<reference path="vendor/jquery-2.0.3.d"/>
///<reference path="vendor/facebook.d"/>
///<reference path="Builder"/>
///<reference path="Views"/>
var Application = (function () {
    function Application(environment) {
        var _this = this;
        this.environment = environment;
        window.fbAsyncInit = function () {
            return _this.facebookInit();
        };
        $(document).ready(function () {
            return _this.onReady();
        });
    }
    Application.prototype.onReady = function () {
    };

    Application.prototype.facebookInit = function () {
        FB.init({
            appId: this.environment.getFacebookAppId(),
            status: true,
            cookie: true
        });
        FB.login(function (response) {
            if (response.authResponse) {
                var documentView = Builder.init(document, true);
                documentView.init(response.authResponse.accessToken);
            } else {
                console.log('not authorized');
            }
        });
    };
    return Application;
})();
//# sourceMappingURL=Application.js.map

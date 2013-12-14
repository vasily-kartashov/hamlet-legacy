///<reference path="Environment"/>
///<reference path="vendor/jquery-2.0.3.d"/>
///<reference path="vendor/facebook.d"/>
///<reference path="Views"/>
///<reference path="HomePage"/>
///<reference path="Container"/>

class Application {

    constructor(private environment: Environment) {
        this.loadFacebookSDK(() => this.initFacebook());
        $(document).ready(() => this.onReady());
    }

    private loadFacebookSDK(callback : () => void) {
        window.fbAsyncInit = callback;
        var d = document;
        var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/es_LA/all.js";
        d.getElementsByTagName('head')[0].appendChild(js);
    }

    private onReady() {}

    private initFacebook() {
        FB.init({
            appId: this.environment.getFacebookAppId(),
            status: true,
            cookie: true
        });
        FB.login(function(response) {
            if (response.authResponse) {
                var scope = Container.load(<HTMLElement>document.documentElement);
                var documentView = <BasePage> scope.getFirstInstance('pageView');
                documentView.init(response.authResponse.accessToken);
            } else {
                console.log('not authorized');
            }
        });
    }
}

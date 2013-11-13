/**
 * Created by danny on 13/11/2013.
 */


///<reference path="Environment"/>
///<reference path="vendor/jquery-2.0.3.d"/>
///<reference path="vendor/facebook.d"/>
///<reference path="Builder"/>
///<reference path="Views"/>

class Application {


    constructor( private environment : Environment)
    {
        this.loadFacebookSDK(()=> this.initFacebook());
        $(document).ready(()=> this.onReady());
    }


    private loadFacebookSDK(callback : Function)
    {
        window.fbAsyncInit = ()=> this.initFacebook();
        (function(d){
            var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/es_LA/all.js";
            d.getElementsByTagName('head')[0].appendChild(js);
        }(document));

    }

    private onReady()
    {

    }

    private initFacebook()
    {
        FB.init({
            appId: this.environment.getFacebookAppId(),
            status: true,
            cookie: true
        });
        FB.login(function(response) {
            if (response.authResponse) {
                var documentView = <Views.Document> Builder.init(document, true);
                documentView.init(response.authResponse.accessToken);
            } else {
                console.log('not authorized');
            }
        });
    }



}
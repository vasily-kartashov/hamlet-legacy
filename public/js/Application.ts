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
        window.fbAsyncInit = ()=> this.facebookInit();
        $(document).ready(()=> this.onReady());
    }

    private onReady()
    {

    }

    private facebookInit()
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
/// <reference path="Application"/>
/// <reference path="Environment"/>

interface Window {
    __environment : EnvironmentJSON
}

new Application(new Environment(window.__environment));

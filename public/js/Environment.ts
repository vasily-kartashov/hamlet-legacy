interface EnvironmentJSON {
    facebookAppId: string;
}

class Environment {

    private facebookAppId;

    constructor(json: EnvironmentJSON) {
        this.facebookAppId = json.facebookAppId;
    }

    public getFacebookAppId(): string {
        return this.facebookAppId;
    }

}

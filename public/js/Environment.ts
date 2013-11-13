/**
 * Created by danny on 13/11/2013.
 */
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

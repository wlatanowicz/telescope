import Http from "@framework/Data/Http";
import RPCPromise from "@app/Client/Common/RPCPromise";
import Coordinates from "@app/ValueObject/Coordinates";

export default class RPC
{
    _http = null;

    constructor(http: Http, baseUrl: string)
    {
        this._http = http;
        this._http.BaseUrl = baseUrl;
    }

    execute(method, params)
    {
        var promise = new RPCPromise();

        this._http.post('/api/job#' + method, {
            jsonrpc: "2.0",
            method: method,
            params: params
        }).done(function(param){
            promise.setState('done', param);
        });

        return promise;
    }

    getInfo(sessionId, resultFile)
    {
        return this._http.get(
            "/api/job/" + sessionId + "/results/" + resultFile + "/info"
        );
    }
}
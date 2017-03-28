import THttp from "@framework/Data/THttp";
import RPCPromise from "@app/Common/RPCPromise";

export default class RPC
{
    _http = null;

    constructor()
    {
        this._http = new THttp();
        this._http.BaseUrl = "http://localhost/radio-telescope/index.php";
    }

    _execute(method, params)
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

    cameraExpose(name, time)
    {
        return this._execute(
            "camera.expose",
            {
                "camera_name": name,
                "time": time
            }
        );
    }

    focuserSetPosition(name, position)
    {
        return this._execute(
            "focuser.set-position",
            {
                "focuser_name": name,
                "position": position
            }
        );
    }

    autofocus(cameraName, focuserName, time, minPosition, maxPosition, x, y, radius, iterations)
    {
        return this._execute(
            "autofocus",
            {
                "camera_name": cameraName,
                "focuser_name": focuserName,
                "measure_name": null,
                "min": minPosition,
                "max": maxPosition,
                "time": time,
                "x": x,
                "y": y,
                "radius": radius,
                "iterations": iterations,
                "partials": 5,
                "tries": [1]
            }
        );
    }
}
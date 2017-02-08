//= require TObject
//= require THttp
//= require RPCPromise

klass('RPC', TObject, {

    _http : null,

    constructor : function () {
        this.base();

        this._http = new THttp();
        this._http.setBaseUrl("http://localhost/radio-telescope/index.php");
    },

    _execute : function(method, params) {
        var promise = new RPCPromise();

        this._http.post('/api/job#' + method, {
            jsonrpc: "2.0",
            method: method,
            params: params
        }).done(function(param){
            promise.setState('done', param);
        });

        return promise;
    },

    getInfo : function(sessionId, resultFile) {
        return this._http.get(
            "/api/job/" + sessionId + "/results/" + resultFile + "/info"
        );
    },

    cameraExpose : function(name, time) {
        return this._execute(
            "camera.expose",
            {
                "camera_name": name,
                "time": time
            }
        );
    },

    focuserSetPosition : function(name, position) {
        return this._execute(
            "focuser.set-position",
            {
                "focuser_name": name,
                "position": position
            }
        );
    },

    autofocus : function(cameraName, focuserName, minPosition, maxPosition, x, y) {
        return this._execute(
            "autofocus",
            {
                "camera_name": cameraName,
                "focuser_name": focuserName,
                "min_position": minPosition,
                "max_position": maxPosition
            }
        );
    }

});
import RPC from "@app/Client/Common/RPC";

export default class Focuser
{
    private rpc: RPC;

    constructor(rpc: RPC) {
        this.rpc = rpc;
    }

    setPosition(name, position)
    {
        return this.rpc.execute(
            "focuser.set-position",
            {
                "focuser_name": name,
                "position": position
            }
        );
    }

    autofocus(cameraName, focuserName, time, minPosition, maxPosition, x, y, radius, iterations)
    {
        return this.rpc.execute(
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
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

    autofocus(cameraName, focuserName, autofocusName, measureName, time, minPosition, maxPosition, x, y, radius, iterations, partials, tries)
    {
        return this.rpc.execute(
            "autofocus",
            {
                "camera_name": cameraName,
                "focuser_name": focuserName,
                "measure_name": measureName,
                "autofocus_name": autofocusName,
                "min": minPosition,
                "max": maxPosition,
                "time": time,
                "x": x,
                "y": y,
                "radius": radius,
                "iterations": iterations,
                "partials": partials,
                "tries": tries.split(',').map((v) => parseInt(v.trim())),
            }
        );
    }

}
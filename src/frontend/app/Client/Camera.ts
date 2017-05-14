import RPC from "@app/Client/Common/RPC";

export default class Camera
{
    private rpc: RPC;

    constructor(rpc: RPC) {
        this.rpc = rpc;
    }

    expose(name, time)
    {
        return this.rpc.execute(
            "camera.expose",
            {
                "camera_name": name,
                "time": time
            }
        );
    }

    getInfo(sessionId, resultFile)
    {
        return this.rpc.getInfo(sessionId, resultFile);
    }
}
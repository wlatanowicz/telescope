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

    getInfo(sessionId, resultFiles)
    {
        let resultFile = resultFiles.filter((f) => f.endsWith('.jpeg'))[0];
        return this.rpc.getInfo(sessionId, resultFile);
    }
}

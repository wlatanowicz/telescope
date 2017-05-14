import RPC from "@app/Client/Common/RPC";
import Coordinates from "@app/ValueObject/Coordinates";

export default class Telescope
{
    private rpc: RPC;

    constructor(rpc: RPC) {
        this.rpc = rpc;
    }

    getPosition(name)
    {
        return this.rpc.execute(
            "telescope.get-position",
            {
                "telescope_name": name,
            }
        );
    }

    setPosition(name, coordinates: Coordinates)
    {
        return this.rpc.execute(
            "telescope.set-position",
            {
                "telescope_name": name,
                "coordinates" : {
                    "right_ascension": coordinates.RightAscension,
                    "declination": coordinates.Declination
                }
            }
        );
    }

}
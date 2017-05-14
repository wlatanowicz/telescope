import Http from "@framework/Data/Http";
import Settings from "@app/Repository/Settings";
import FocuserInterface from "@app/Client/FocuserInterface";

export default class Node extends Http implements FocuserInterface
{
    private position: number = 1000;
    private targetPosition: number = 1000;
    private maxSpeed: number = 100;

    get Position(): number
    {
        return this.position;
    }

    get TargetPosition(): number
    {
        return this.targetPosition;
    }

    constructor(settings: Settings)
    {
        super();
        this.BaseUrl = "http://" + settings.IP;
    }

    applyHeaders(xhttp)
    {
        super.applyHeaders(xhttp);
        xhttp.timeout = 5000;
    }

    getPosition()
    {
        return this.get("", {}).done(this.update.bind(this));
    }

    setPosition(target: number)
    {
        this.targetPosition = target;
        return this.post("", {}, {
            "targetPosition": target,
            "maxSpeed": this.maxSpeed
        }).done(this.update.bind(this));
    }

    private update(result)
    {
        this.position = result.position;
        if (this.targetPosition === null) {
            this.targetPosition = result.position;
        }
    }
}
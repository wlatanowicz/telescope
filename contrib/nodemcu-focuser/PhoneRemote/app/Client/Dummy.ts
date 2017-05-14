import FocuserInterface from "@app/Client/FocuserInterface";
import HttpPromise from "@framework/Data/HttpPromise";

export default class Dummy implements FocuserInterface
{
    private position: number = 1000;
    private targetPosition: number = 1000;

    get Position(): number
    {
        return this.position;
    }

    get TargetPosition(): number
    {
        return this.targetPosition;
    }

    getPosition()
    {
        var promise = new HttpPromise();
        setTimeout(function() {
            promise.setState('done', this.prepareResponse());
        }.bind(this), 2000);
        return promise;
    }

    setPosition(target: number)
    {
        this.targetPosition = target;
        var promise = new HttpPromise();
        setTimeout(function() {
            this.position = target;
            promise.setState('done', this.prepareResponse());
        }.bind(this), 2000);
        return promise;
    }

    private prepareResponse()
    {
        return {
            response: {position: this.position, target: this.position}
        }
    }
}
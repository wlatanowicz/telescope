export default class PositionCalibration
{
    protected _angle;
    protected _length;

    constructor(angleDiff:number, lengthRatio:number)
    {
        this._length = lengthRatio;
        this._angle = angleDiff;
    }

    get LengthRatio()
    {
        return this._length;
    }

    get AngleDiff()
    {
        return this._angle;
    }
}

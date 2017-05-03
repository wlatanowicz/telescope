export default class Coordinates
{
    private _rightAscension: number;
    private _declination: number;

    constructor(rightAscension: number, declination: number) {
        this._rightAscension = rightAscension;
        this._declination = declination;
    }

    get RightAscension(): number {
        return this._rightAscension;
    }

    get Declination(): number {
        return this._declination;
    }
}

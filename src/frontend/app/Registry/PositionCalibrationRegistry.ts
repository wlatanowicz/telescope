import PositionCalibration from "@app/ValueObject/PositionCalibration";

export default class PositionCalibrationRegistry
{
    static ANGLE_KEY = 'PositionCalibration.shift';
    static LENGTH_KEY = 'PositionCalibration.length';

    store(positionCalibration:PositionCalibration){
        localStorage.setItem(PositionCalibrationRegistry.ANGLE_KEY, positionCalibration.AngleDiff);
        localStorage.setItem(PositionCalibrationRegistry.LENGTH_KEY, positionCalibration.LengthRatio);
    }

    read():PositionCalibration
    {
        return new PositionCalibration(
            parseFloat(localStorage.getItem(PositionCalibrationRegistry.ANGLE_KEY)),
            parseFloat(localStorage.getItem(PositionCalibrationRegistry.LENGTH_KEY))
        )
    }
}
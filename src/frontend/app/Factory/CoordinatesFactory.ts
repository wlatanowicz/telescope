import PixelCoordinates from "@app/ValueObject/PixelCoordinates";
import Coordinates from "@app/ValueObject/Coordinates";
import PositionCalibration from "@app/ValueObject/PositionCalibration";

export default class CoordinatesFactory
{
    fromPositionCalibration(
        calibration: PositionCalibration,
        originalPosition: Coordinates,
        originalPixel: PixelCoordinates,
        targetPixel: PixelCoordinates
    ): Coordinates {
        let imageDiff = Math.sqrt(
            Math.pow(targetPixel.x - originalPixel.x, 2)
            + Math.pow(targetPixel.y - originalPixel.y, 2)
        );

        let imageAngle = Math.atan2(
            targetPixel.x - originalPixel.x,
            targetPixel.y - originalPixel.y
        );

        let diff = imageDiff * calibration.LengthRatio;
        let angle = imageAngle + calibration.AngleDiff;

        return new Coordinates(
            originalPosition.RightAscension + ( (diff * Math.sin(angle)) * 24 / 360 ),
            originalPosition.Declination + (diff * Math.cos(angle))
        );
    }
}
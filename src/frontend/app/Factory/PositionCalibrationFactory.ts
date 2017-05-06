import Coordinates from "@app/ValueObject/Coordinates";
import PixelCoordinates from "@app/ValueObject/PixelCoordinates";
import PositionCalibration from "@app/ValueObject/PositionCalibration";

export default class PositionCalibrationFactory
{
    fromPositionAndImageOffsets(
        primaryCoordinates: Coordinates,
        secondaryCoordinates: Coordinates,
        primaryStar: PixelCoordinates,
        secondaryStar: PixelCoordinates
    ): PositionCalibration {
        let primaryRa = 360 * primaryCoordinates.RightAscension / 24;
        let secondaryRa = 360 * secondaryCoordinates.RightAscension / 24;
        let primaryDec = primaryCoordinates.Declination;
        let secondaryDec = secondaryCoordinates.Declination;

        let starAngle = Math.atan2(
            secondaryStar.x - primaryStar.x,
            secondaryStar.y - primaryStar.y
        );

        let slewAngle = Math.atan2(
            secondaryRa - primaryRa,
            secondaryDec - primaryDec
        );

        let diffAngle = starAngle - slewAngle;

        let starShift = Math.sqrt(
            Math.pow(secondaryStar.x - primaryStar.x, 2)
            + Math.pow(secondaryStar.y - primaryStar.y, 2)
        );

        let slewShift = Math.sqrt(
            Math.pow(primaryRa - secondaryRa, 2)
            + Math.pow(primaryDec - secondaryDec, 2)
        );

        let diffShift = slewShift == starShift
            ? 1
            : slewShift / starShift;

        while (diffAngle > Math.PI) {
            diffAngle -= Math.PI;
        }

        while (diffAngle < -Math.PI) {
            diffAngle += Math.PI;
        }

        return new PositionCalibration(
            diffAngle,
            diffShift
        );
    }
}
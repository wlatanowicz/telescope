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
            primaryStar.y - secondaryStar.y,
            secondaryStar.x - primaryStar.x
        );

        let slewAngle = Math.atan2(
            secondaryDec - primaryDec,
            secondaryRa - primaryRa
        );

        let diffAngle = Math.PI + starAngle - slewAngle;

        let starShift = Math.sqrt(
            Math.pow(secondaryStar.x - primaryStar.x, 2)
            + Math.pow(secondaryStar.y - primaryStar.y, 2)
        );

        let slewShift = Math.sqrt(
            Math.pow(primaryRa - secondaryRa, 2)
            + Math.pow(primaryDec - secondaryDec, 2)
        );

        let diffShift = slewShift / starShift;

        while (diffAngle > Math.PI) {
            diffAngle -= 2 * Math.PI;
        }

        while (diffAngle < -Math.PI) {
            diffAngle += 2 * Math.PI;
        }

        return new PositionCalibration(
            diffAngle,
            diffShift
        );
    }

    calculateTargetCoordinates(
        calibration: PositionCalibration,
        primaryCoordinates: Coordinates,
        primaryStar: PixelCoordinates,
        secondaryStar: PixelCoordinates
    ): Coordinates {
        let starAngle = Math.atan2(
            primaryStar.y - secondaryStar.y,
            secondaryStar.x - primaryStar.x
        );

        let starShift = Math.sqrt(
            Math.pow(secondaryStar.x - primaryStar.x, 2)
            + Math.pow(secondaryStar.y - primaryStar.y, 2)
        );

        let slewAngle = Math.PI + starAngle + calibration.AngleDiff;
        let slewShift = starShift * calibration.LengthRatio;

        let deltaRa = Math.cos(slewAngle) * slewShift;
        let deltaDec = Math.sin(slewAngle) * slewShift;

        deltaRa = 24.0 * deltaRa / 360.0;

        return new Coordinates(
            primaryCoordinates.RightAscension + deltaRa,
            primaryCoordinates.Declination + deltaDec
        );
    }
}
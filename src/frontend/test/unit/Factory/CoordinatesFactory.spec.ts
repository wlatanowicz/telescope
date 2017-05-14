require('module-alias/register');
import { Test, TestCase } from "alsatian";
import { Expect } from "@test/tools/Expect/ExpectCoordinates";

import CoordinatesFactory from "@app/Factory/CoordinatesFactory";
import Coordinates from "@app/ValueObject/Coordinates";
import PixelCoordinates from "@app/ValueObject/PixelCoordinates";
import PositionCalibration from "@app/ValueObject/PositionCalibration";

export class PositionCalibrationFactorySpec {

    @Test()
    @TestCase(
        new Coordinates(0, 0), new Coordinates(0, 0),
        new PixelCoordinates(0, 0), new PixelCoordinates(0, 0),
        new PositionCalibration(0, 1)
    )
    @TestCase(
        new Coordinates(0, 0), new Coordinates(0.066666, 1),
        new PixelCoordinates(0, 0), new PixelCoordinates(2, 2),
        new PositionCalibration(0, 0.5)
    )
    public exampleTest(
        primaryCoordinates: Coordinates,
        expectedCoordinates: Coordinates,
        primaryPixel: PixelCoordinates,
        targetPixel: PixelCoordinates,
        calibration: PositionCalibration
    ) {
        var f = new CoordinatesFactory();
        var result = f.fromPositionCalibration(
            calibration,
            primaryCoordinates,
            primaryPixel,
            targetPixel
        );
        Expect(result).toEqualWithTolerance(expectedCoordinates);
    }
}

require('module-alias/register');
import { Test, TestCase } from "alsatian";
import { Expect } from "@test/tools/Expect/ExpectPositionCalibration";

import PositionCalibrationFactory from "../../../app/Factory/PositionCalibrationFactory";
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
    public itShouldCalculateCalibrationData(
        primaryCoordinates: Coordinates,
        secondaryCoordinates: Coordinates,
        primaryPixel: PixelCoordinates,
        secondaryPixel: PixelCoordinates,
        expectedCalibration: PositionCalibration
    ) {
        var f = new PositionCalibrationFactory();
        var result = f.fromPositionAndImageOffsets(
            primaryCoordinates,
            secondaryCoordinates,
            primaryPixel,
            secondaryPixel
        );
        Expect(result).toEqualWithTolerance(expectedCalibration);
    }
}

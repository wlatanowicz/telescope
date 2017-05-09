require('module-alias/register');
import { Matcher, MatchError } from "alsatian";

import PositionCalibration from "@app/ValueObject/PositionCalibration";

class MatcherExtension extends Matcher
{
    toEqualWithTolerance(expectedValue: PositionCalibration, tolerance: number = 0.001): void
    {
        let equals = ((expectedValue.AngleDiff - tolerance) < this.actualValue.AngleDiff)
            && ((expectedValue.AngleDiff + tolerance) > this.actualValue.AngleDiff)
            && ((expectedValue.LengthRatio - tolerance) < this.actualValue.LengthRatio)
            && ((expectedValue.LengthRatio + tolerance) > this.actualValue.LengthRatio);

        if (equals !== this.shouldMatch) {
            throw new MatchError(
                this.shouldMatch
                    ? `Expected ${JSON.stringify(this.actualValue)} to be equal ${JSON.stringify(expectedValue)}`
                    : `Expected ${JSON.stringify(this.actualValue)} not to be equal ${JSON.stringify(expectedValue)}`,
                expectedValue,
                this.actualValue
            );
        }
    }
}

export function Expect(value: any) { return new MatcherExtension(value); }

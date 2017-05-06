import Coordinates from "@app/ValueObject/Coordinates";
require('module-alias/register');
import { Matcher, MatchError } from "alsatian";

class MatcherExtension extends Matcher
{
    toEqualWithTolerance(expectedValue: Coordinates, tolerance: number = 0.001): void
    {
        let equals = ((expectedValue.Declination - tolerance) < this.actualValue.Declination)
            && ((expectedValue.Declination + tolerance) > this.actualValue.Declination)
            && ((expectedValue.RightAscension - tolerance) < this.actualValue.RightAscension)
            && ((expectedValue.RightAscension + tolerance) > this.actualValue.RightAscension);

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

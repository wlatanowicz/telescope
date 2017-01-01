<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\AutoFocus;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\AutofocusPoint;
use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Routine\AutoFocusInterface;
use wlatanowicz\AppBundle\Routine\MeasureInterface;

class SimpleRecursive implements AutoFocusInterface
{
    /**
     * @var AutofocusPoint[]
     */
    private $measureCache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->measureCache = [];
    }

    public function autofocus(
        MeasureInterface $measure,
        ImagickCameraInterface $camera,
        FocuserInterface $focuser,
        int $partials,
        int $iterations,
        int $minPosition,
        int $maxPosition,
        int $time
    ): AutofocusResult {
        $this->measureCache = [];
        $results = $this->recursiveAutoFocus(
            $measure,
            $camera,
            $focuser,
            $partials,
            $iterations,
            $minPosition,
            $maxPosition,
            $time
        );

        return $this->prepareResult($results);
    }

    /**
     * @param AutofocusPoint[] $points
     * @return AutofocusResult
     */
    private function prepareResult(array $points): AutofocusResult
    {
        /**
         * @var $uniquePoints AutofocusPoint[]
         */
        $uniquePoints = [];

        foreach ($points as $point) {
            if (!$this->pointInArrayByPosition($uniquePoints, $point)) {
                $uniquePoints[] = $point;
            }
        }

        $bestIndices = [];
        $bestMeasure = null;

        foreach ($uniquePoints as $index => $point) {
            if ($bestMeasure === null
                || $point->getMeasure() < $bestMeasure) {
                $bestIndices = [$index];
                $bestMeasure = $point->getMeasure();
            } elseif ($point->getMeasure() === $bestMeasure) {
                $bestIndices[] = $index;
            }
        }

        $bestIndexIndex = (int)floor((count($bestIndices)-1) / 2);

        $best = $uniquePoints[$bestIndices[$bestIndexIndex]];

        usort(
            $uniquePoints,
            function (AutofocusPoint $a, AutofocusPoint $b) {
                return $a->getPosition() - $b->getPosition();
            }
        );

        return new AutofocusResult(
            $best,
            $uniquePoints
        );
    }

    /**
     * @param AutofocusPoint[] $points
     * @param AutofocusPoint $searchPoint
     */
    private function pointInArrayByPosition(array $points, AutofocusPoint $searchPoint): bool
    {
        foreach ($points as $point) {
            if ($point->getPosition() === $searchPoint->getPosition()) {
                return true;
            }
        }
        return false;
    }

    private function recursiveAutoFocus(
        MeasureInterface $measure,
        ImagickCameraInterface $camera,
        FocuserInterface $focuser,
        int $partials,
        int $iterations,
        int $min,
        int $max,
        int $time,
        int $iteration = 0
    ): array {
        $reverse = $iteration % 2 === 1;
        $points = $this->generatePoints($partials, $min, $max, $reverse);
        $measurements = [];

        foreach ($points as $index=>$position) {
            $measurements[] = $this->getMeasureForPosition(
                $measure,
                $camera,
                $focuser,
                $position,
                $time
            );
        }

        $bestMeasurement = null;
        $bestIndex = null;
        foreach ($measurements as $index => $measurement) {
            /**
             * @var $measure AutofocusPoint
             */
            if ($bestMeasurement === null
                || $measurement->getMeasure() < $bestMeasurement) {
                $bestMeasurement = $measurement->getMeasure();
                $bestIndex = $index;
            }
        }

        $newMin = $points[$bestIndex-1];
        $newMax = $points[$bestIndex+1];

        $result = $measurements;

        if (($iteration + 1) < $iterations) {
            $nextResult = $this->recursiveAutoFocus(
                $measure,
                $camera,
                $focuser,
                $partials,
                $iterations,
                $newMin,
                $newMax,
                $time,
                $iteration + 1
            );
            $result = array_merge($result, $nextResult);
        }

        return $result;
    }

    /**
     * @param int $min
     * @param int $max
     * @return int[]
     */
    private function generatePoints(int $partials, int $min, int $max, bool $reverse): array
    {
        $separation = ($max - $min) / ($partials - 1);

        for ($i=0; $i < ($partials - 1); $i++) {
            $points[] = (int)round($min + $i * $separation);
        }
        $points[] = $max;

        $points = $reverse
            ? array_reverse($points)
            : $points;

        return $points;
    }

    private function getMeasureForPosition(
        MeasureInterface $measure,
        ImagickCameraInterface $camera,
        FocuserInterface $focuser,
        int $position,
        int $time
    ): AutofocusPoint {
        if ( !isset($this->measureCache[$position])) {
            $focuser->setPosition($position);

            $image = $camera->exposure($time);
            $measure = $measure->measure($image);
            $this->measureCache[$position] = new AutofocusPoint(
                $position,
                $measure,
                $image
            );
        }
        return $this->measureCache[$position];
    }
}

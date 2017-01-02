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

    /**
     * @var int
     */
    private $partials;

    /**
     * @var int[]
     */
    private $tries;

    /**
     * @var int
     */
    private $iterations;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->measureCache = [];

        $this->partials = 5;
        $this->iterations = 5;
        $this->tries = [1];
    }

    /**
     * @param int $partials
     */
    public function setPartials(int $partials)
    {
        $this->partials = $partials;
    }

    /**
     * @param int $iterations
     */
    public function setIterations(int $iterations)
    {
        $this->iterations = $iterations;
    }

    /**
     * @param int $tries
     */
    public function setTries(int $tries)
    {
        $this->tries = [$tries];
    }

    public function setTriesArray(array $tries)
    {
        $this->tries = $tries;
    }

    private function getTriesForIteration(int $iteration)
    {
        return isset($this->tries[$iteration])
            ? $this->tries[$iteration]
            : $this->tries[count($this->tries) - 1];
    }

    public function autofocus(
        MeasureInterface $measure,
        ImagickCameraInterface $camera,
        FocuserInterface $focuser,
        int $minPosition,
        int $maxPosition,
        int $time
    ): AutofocusResult {
        $this->measureCache = [];
        $start = time();

        $results = $this->recursiveAutoFocus(
            $measure,
            $camera,
            $focuser,
            $minPosition,
            $maxPosition,
            $time
        );

        $result = $this->prepareResult($results);

        $finish = time();

        $this->logger->info(
            "Finish after {time} seconds (position={position}, measurement={measurement})",
            [
                "time" => $finish - $start,
                "position" => $result->getMaximum()->getPosition(),
                "measurement" => $result->getMaximum()->getMeasure(),
            ]
        );
        return $result;
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

        usort(
            $uniquePoints,
            function (AutofocusPoint $a, AutofocusPoint $b) {
                return $a->getPosition() - $b->getPosition();
            }
        );

        foreach ($uniquePoints as $index => $point) {
            if ($bestMeasure === null
                || $point->getMeasure() < $bestMeasure
            ) {
                $bestIndices = [$index];
                $bestMeasure = $point->getMeasure();
            } elseif ($point->getMeasure() === $bestMeasure) {
                $bestIndices[] = $index;
            }
        }

        $bestIndexIndex = (int)floor((count($bestIndices)-1) / 2);

        $best = $uniquePoints[$bestIndices[$bestIndexIndex]];

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
        int $min,
        int $max,
        int $time,
        int $iteration = 0
    ): array {
        $reverse = $iteration % 2 === 1;
        $points = $this->generatePoints($min, $max, $reverse);
        $measurements = [];

        $this->logger->info(
            "Starting iteration {iteration} of {iterations} (points={points})",
            [
                "iteration" => $iteration + 1,
                "iterations" => $this->iterations,
                "points" => \json_encode($points),
            ]
        );

        foreach ($points as $index=>$position) {
            $measurements[] = $this->getMeasureForPosition(
                $measure,
                $camera,
                $focuser,
                $position,
                $time,
                $iteration,
                $index
            );
        }

        $bestMeasurement = null;
        $bestIndices = [];

        foreach ($measurements as $index => $measurement) {
            /**
             * @var $measurement AutofocusPoint
             */
            if ($bestMeasurement === null
                || $measurement->getMeasure() < $bestMeasurement) {
                $bestMeasurement = $measurement->getMeasure();
                $bestIndices = [$index];
            } elseif ($measurement->getMeasure() === $bestMeasurement) {
                $bestIndices[] = $index;
            }
        }

        $bestIndexIndex = (int)floor((count($bestIndices)-1) / 2);

        $bestIndex = $bestIndices[$bestIndexIndex];

        $newMin = $points[$bestIndex-1];
        $newMax = $points[$bestIndex+1];

        $result = $measurements;

        $this->logger->info(
            "Finished iteration {iteration} of {iterations}",
            [
                "iteration" => $iteration + 1,
                "iterations" => $this->iterations,
            ]
        );

        if (($iteration + 1) < $this->iterations) {
            $nextResult = $this->recursiveAutoFocus(
                $measure,
                $camera,
                $focuser,
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
    private function generatePoints(int $min, int $max, bool $reverse): array
    {
        $separation = ($max - $min) / ($this->partials - 1);

        for ($i=0; $i < ($this->partials - 1); $i++) {
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
        int $time,
        int $iteration,
        int $partial
    ): AutofocusPoint {
        if ( !isset($this->measureCache[$position])) {
            $focuser->setPosition($position);

            $image = null;
            $measurement = 0;

            $tries = $this->getTriesForIteration($iteration);

            for ($i = 0; $i < $tries; $i++) {
                $this->logger->info(
                    "Exposing image for i={iteration}/{iterations} p={partial}/{partials} t={try}/{tries} (position={position})",
                    [
                        "iteration" => $iteration + 1,
                        "iterations" => $this->iterations + 1,
                        "partial" => $partial + 1,
                        "partials" => $this->partials,
                        "try" => $i + 1,
                        "tries" => $tries,
                        "position" => $position,
                    ]
                );

                $currentImage = $camera->exposure($time);
                $currentMeasurement = $measure->measure($currentImage);

                $this->logger->info(
                    "Measured image for i={iteration}/{iterations} p={partial}/{partials} t={try}/{tries} (measurement={measurement}, position={position})",
                    [
                        "iteration" => $iteration + 1,
                        "iterations" => $this->iterations + 1,
                        "partial" => $partial + 1,
                        "partials" => $this->partials,
                        "try" => $i + 1,
                        "tries" => $tries,
                        "position" => $position,
                        "measurement" => round($currentMeasurement, 4),
                    ]
                );

                if ($i === 0 ||
                    ($currentMeasurement > 0 && $currentMeasurement < $measurement) ) {
                    $image = $currentImage;
                    $measurement = $currentMeasurement;
                }
            }

            $this->measureCache[$position] = new AutofocusPoint(
                $position,
                $measurement,
                $image
            );
        } else {
            $this->logger->info(
                "Using cached measurement for i={iteration}/{iterations} p={partial}/{partials} (measurement={measurement}, position={position})",
                [
                    "iteration" => $iteration + 1,
                    "iterations" => $this->iterations + 1,
                    "partial" => $partial + 1,
                    "partials" => $this->partials,
                    "position" => $position,
                    "measurement" => round($this->measureCache[$position]->getMeasure(), 4)
                ]
            );
        }
        return $this->measureCache[$position];
    }
}

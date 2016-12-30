<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\AutofocusPoint;
use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class AutoFocus
{
    /**
     * @var MeasureInterface
     */
    private $measure;

    /**
     * @var ImagickCameraInterface
     */
    private $camera;

    /**
     * @var FocuserInterface
     */
    private $focuser;

    /**
     * @var int
     */
    private $partials;

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var AutofocusPoint[]
     */
    private $measureCache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        MeasureInterface $measure,
        ImagickCameraInterface $camera,
        FocuserInterface $focuser,
        int $partials,
        int $iterations,
        LoggerInterface $logger
    ) {
        $this->measure = $measure;
        $this->camera = $camera;
        $this->focuser = $focuser;
        $this->partials = $partials;
        $this->iterations = $iterations;

        $this->logger = $logger;

        $this->measureCache = [];
    }

    public function autofocus(int $minPosition, int $maxPosition, int $time): AutofocusResult
    {
        $results = $this->recursiveAutoFocus(
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
        int $min,
        int $max,
        int $time,
        int $iteration = 0
    ): array {
        $reverse = $iteration % 2 === 1;
        $points = $this->generatePoints($min, $max, $reverse);
        $measures = [];

        foreach ($points as $index=>$position) {
            $measures[] = $this->getMeasureForPosition(
                $position,
                $time
            );
        }

        $bestMeasure = null;
        $bestIndex = null;
        foreach ($measures as $index => $measure) {
            /**
             * @var $measure AutofocusPoint
             */
            if ($bestMeasure === null
                || $measure->getMeasure() < $bestMeasure) {
                $bestMeasure = $measure->getMeasure();
                $bestIndex = $index;
            }
        }

        $newMin = $points[$bestIndex-1];
        $newMax = $points[$bestIndex+1];

        $result = $measures;

        if (($iteration + 1) < $this->iterations) {
            $nextResult = $this->recursiveAutoFocus(
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

    private function getMeasureForPosition(int $position, int $time): AutofocusPoint
    {
        if ( !isset($this->measureCache[$position])) {
            $this->focuser->setPosition($position);

            $image = $this->camera->exposure($time);
            $measure = $this->measure->measure($image);
            $this->measureCache[$position] = new AutofocusPoint(
                $position,
                $measure,
                $image
            );
        }
        return $this->measureCache[$position];
    }
}

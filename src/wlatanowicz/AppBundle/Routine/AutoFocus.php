<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Hardware\GdCameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class AutoFocus
{
    /**
     * @var MeasureInterface
     */
    private $measure;

    /**
     * @var GdCameraInterface
     */
    private $camera;

    /**
     * @var FocuserInterface
     */
    private $focuser;

    private $minPosition;

    private $maxPosition;

    private $partials;

    private $iterations;

    private $measureCache;

    public function __construct(
        MeasureInterface $measure,
        GdCameraInterface $camera,
        FocuserInterface $focuser,
        int $minPosition,
        int $maxPosition,
        int $partials,
        int $iterations
    ) {
        $this->measure = $measure;
        $this->camera = $camera;
        $this->focuser = $focuser;
        $this->minPosition = $minPosition;
        $this->maxPosition = $maxPosition;
        $this->partials = $partials;
        $this->iterations = $iterations;

        $this->measureCache = [];
    }

    public function autofocus(int $time, int $x, int $y, int $width, int $height): int
    {
        $results = $this->recursiveAutoFocus(
            $this->minPosition,
            $this->maxPosition,
            $time,
            $x,
            $y,
            $width,
            $height
        );

        usort(
            $results,
            function ($a, $b) {
                return $a['measure'] - $b['measure'];
            }
        );

        return $results[0]['position'];
    }

    private function recursiveAutoFocus(
        int $min,
        int $max,
        int $time,
        int $x,
        int $y,
        int $width,
        int $height,
        int $iteration = 0
    ): array {
        $reverse = $iteration % 2 === 1;
        $points = $this->generatePoints($min, $max, $reverse);
        $measures = [];

        foreach ($points as $index=>$position) {
            $measures[] = [
                "position" => $position,
                "measure" => $this->getMeasureForPosition(
                    $position,
                    $time,
                    $x,
                    $y,
                    $width,
                    $height
                ),
            ];
        }

        $bestMeasure = null;
        $bestIndex = null;
        foreach ($measures as $index=>$measure) {
            if ($bestMeasure === null
                || $measure['measure'] < $bestMeasure) {
                $bestMeasure = $measure['measure'];
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
                $x,
                $y,
                $width,
                $height,
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

    private function getMeasureForPosition(int $position, int $time, int $x, int $y, int $width, int $height): float
    {
        if ( !isset($this->measureCache[$position])) {
            $this->focuser->setPosition($position);

            $image = $this->camera->exposure($time);
            $this->measureCache[$position] = $this->measure->measure($image);
        }
        return $this->measureCache[$position];
    }
}

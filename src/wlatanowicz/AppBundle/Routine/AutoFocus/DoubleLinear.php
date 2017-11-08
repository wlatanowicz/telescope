<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine\AutoFocus;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\AutofocusPoint;
use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Data\Point;
use wlatanowicz\AppBundle\Data\Polynomial;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Routine\AutoFocusInterface;
use wlatanowicz\AppBundle\Routine\Math\LinearRegression;
use wlatanowicz\AppBundle\Routine\Measure\Exception\CannotMeasureException;
use wlatanowicz\AppBundle\Routine\MeasureInterface;

class DoubleLinear implements AutoFocusInterface
{
    protected static $OPTION_DEFAULTS = [
        "tries" => [1],
        "partials" => 15,
    ];

    /**
     * @var LinearRegression
     */
    private $linearRegression;

    /**
     * @var int
     */
    private $partials;

    /**
     * @var int
     */
    private $tries;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImagickImageFactory
     */
    private $imagickImageFactory;

    public function __construct(
        LoggerInterface $logger,
        ImagickImageFactory $imagickImageFactory,
        LinearRegression $linearRegression
    ) {
        $this->imagickImageFactory = $imagickImageFactory;
        $this->logger = $logger;
        $this->linearRegression = $linearRegression;
    }

    public function autofocus(
        MeasureInterface $measure,
        CameraInterface $camera,
        FocuserInterface $focuser,
        int $minPosition,
        int $maxPosition,
        int $time,
        array $options = []
    ): AutofocusResult {
        $this->applyOptions($options);
        $points = $this->generatePoints($minPosition, $maxPosition);
        $measures = $this->getMeasures($measure, $points, $camera, $focuser, $time);

        list($descending, $ascending) = $this->splitVShape($measures);

        $descendingPolynomial = $this->interpolateLine($descending);
        $ascendingPolynomial = $this->interpolateLine($ascending);

        $bestPosition = $this->calculateBestPosition($descendingPolynomial, $ascendingPolynomial);

        $best = $this->getMeasureForPosition(
            $measure,
            $camera,
            $focuser,
            $bestPosition,
            $time,
            -1
        );

        $measures[] = $best;

        usort(
            $measures,
            function (AutofocusPoint $a, AutofocusPoint $b) {
                return $a->getPosition() - $b->getPosition();
            }
        );

        return new AutofocusResult(
            $best,
            $measures
        );
    }

    /**
     * @param AutofocusPoint[] $measures
     * @return array
     */
    private function splitVShape(array $measures): array
    {
        $minimalValue = null;
        $minimalIndexes = [];

        foreach ($measures as $index => $measure) {
            if ($minimalValue === null || $measure->getMeasure() < $minimalValue) {
                $minimalValue = $measure->getMeasure();
                $minimalIndexes = [$index];
            } else if($measure->getMeasure() == $minimalValue) {
                $minimalIndexes[] = $index;
            }
        }

        $descending = [];
        $ascending = [];

        foreach ($measures as $index => $measure) {
            if ($index <= $minimalIndexes[0]) {
                $descending[] = $measure;
            }
            if ($index >= $minimalIndexes[count($minimalIndexes) - 1]) {
                $ascending[] = $measure;
            }
        }

        return [$descending, $ascending];
    }

    private function generatePoints(int $min, int $max): array
    {
        $separation = ($max - $min) / ($this->partials - 1);

        for ($i=0; $i < ($this->partials - 1); $i++) {
            $points[] = (int)round($min + $i * $separation);
        }
        $points[] = $max;

        return $points;
    }

    private function getMeasures(
        MeasureInterface $measure,
        array $positions,
        CameraInterface $camera,
        FocuserInterface $focuser,
        int $time
    ): array {
        $measures = [];
        foreach ($positions as $partial => $position) {
            $measures[] = $this->getMeasureForPosition(
                $measure,
                $camera,
                $focuser,
                $position,
                $time,
                $partial
            );

        }
        return $measures;
    }

    private function applyOptions(array $options)
    {
        $options = array_replace(self::$OPTION_DEFAULTS, $options);

        if (is_array($options['tries'])) {
            $this->setTries($options['tries'][0]);
        } else {
            $this->setTries($options['tries']);
        }

        $this->setPartials($options['partials']);
    }

    private function setPartials(int $partials)
    {
        $this->partials = $partials;
    }

    private function setTries(int $tries)
    {
        $this->tries = $tries;
    }

    /**
     * @param MeasureInterface $measure
     * @param CameraInterface $camera
     * @param FocuserInterface $focuser
     * @param int $position
     * @param int $time
     * @param int $partial
     * @return AutofocusPoint
     * @throws \Exception
     */
    private function getMeasureForPosition(
        MeasureInterface $measure,
        CameraInterface $camera,
        FocuserInterface $focuser,
        int $position,
        int $time,
        int $partial
    ): AutofocusPoint {
        $focuser->setPosition($position);

        $image = null;
        $measurement = 0;

        $tries = $this->tries;

        for ($i = 0; $i < $tries; $i++) {
            $this->logger->info(
                "Exposing image for p={partial}/{partials} t={try}/{tries} (position={position})",
                [
                    "partial" => $partial + 1,
                    "partials" => $this->partials,
                    "try" => $i + 1,
                    "tries" => $tries,
                    "position" => $position,
                ]
            );

            $currentMeasurement = null;
            $currentImage = $camera->exposure($time);

            $image = $this->imagickImageFactory->fromBinaryImages($currentImage);

            try {
                $currentMeasurement = $measure->measure($image);
            } catch(CannotMeasureException $ex) {
                $this->logger->warning(
                    "Measurement failed ({message})",
                    [
                        "message" => $ex->getMessage(),
                        "exception" => $ex,
                    ]
                );
            }

            $this->logger->info(
                "Measured image for p={partial}/{partials} t={try}/{tries} (measurement={measurement}, position={position})",
                [
                    "partial" => $partial + 1,
                    "partials" => $this->partials,
                    "try" => $i + 1,
                    "tries" => $tries,
                    "position" => $position,
                    "measurement" => $currentMeasurement !== null
                        ? round($currentMeasurement, 4)
                        : "NULL",
                ]
            );

            if ($i === 0 ||
                ($currentMeasurement !== null && $currentMeasurement < $measurement) ) {
                $image = $currentImage;
                $measurement = $currentMeasurement;
            }
        }

        if ($measurement === null) {
            $this->logger->error(
                "Autofocus failed: Cannot measure focus (position={position})",
                [
                    "position" => $position,
                ]
            );
            throw new \Exception("Autofocus failed: Cannot measure focus");
        }

        return new AutofocusPoint(
            $position,
            $measurement,
            $image
        );
    }

    private function interpolateLine(array $measurePoints): Polynomial
    {
        $points = [];
        foreach($measurePoints as $mp) {
            /**
             * @var $mp AutofocusPoint
             */
            $points[] = new Point(
                $mp->getPosition(),
                $mp->getMeasure()
            );
        }

        return $this->linearRegression->calculate($points);
    }

    private function calculateBestPosition(Polynomial $asc, Polynomial $desc): int
    {
        $position = ($desc->getCoefficient(1) - $asc->getCoefficient(1))
            / ($asc->getCoefficient(0) - $desc->getCoefficient(0));

        return (int)round($position);
    }
}

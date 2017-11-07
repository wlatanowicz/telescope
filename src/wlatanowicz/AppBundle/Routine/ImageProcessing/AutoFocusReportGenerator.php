<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\ImageProcessing;

use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;

class AutoFocusReportGenerator
{
    /**
     * @var ImagickImageFactory
     */
    private $imagickImageFactory;

    /**
     * @var ImagickCircleCrop
     */
    private $imagickCircleCrop;

    /**
     * @var int|null
     */
    private $starRadius;

    /**
     * @var int|null
     */
    private $starX;

    /**
     * @var int|null
     */
    private $starY;

    /**
     * AutoFocusReport constructor.
     * @param ImagickImageFactory $imagickImageFactory
     */
    public function __construct(ImagickImageFactory $imagickImageFactory, ImagickCircleCrop $imagickCircleCrop)
    {
        $this->imagickImageFactory = $imagickImageFactory;
        $this->imagickCircleCrop = $imagickCircleCrop;
    }

    public function setStar(int $radius, int $x = null, int $y = null)
    {
        $this->starX = $x;
        $this->starY = $y;
        $this->starRadius = $radius;
    }

    public function generateReport(AutofocusResult $autofocusResult): ImagickImage
    {
        if ($this->starRadius) {
            $tileHeight = $this->starRadius * 2;
            $tileWidth = $this->starRadius * 2;
        } else {
            $tileWidth = $this->imagickImageFactory->fromBinaryImages($autofocusResult->getMaximum()->getImage())->getWidth();
            $tileHeight = $this->imagickImageFactory->fromBinaryImages($autofocusResult->getMaximum()->getImage())->getHeight();
        }
        $tileCount = count($autofocusResult->getPoints());

        $columnCount = (int)ceil(sqrt($tileCount));
        $rowCount = (int)ceil($tileCount / $columnCount);

        $annotationHeight = 40;

        $imagick = new \Imagick();
        $imagick->newImage(
            $tileWidth * $columnCount,
            ($annotationHeight + $tileHeight) * $rowCount,
            new \ImagickPixel('black')
        );
        $imagick->setImageFormat('jpeg');

        $i = 0;
        for ($y =0 ; $y < $rowCount; $y++) {
            for ($x = 0; $x < $columnCount; $x++) {
                $posX = $x * $tileWidth;
                $posY = $y * ($tileHeight + $annotationHeight);

                if (isset($autofocusResult->getPoints()[$i])) {

                    $tile = $this->imagickImageFactory->fromBinaryImages($autofocusResult->getPoints()[$i]->getImage());

                    if ($this->starRadius) {
                        $tile = $this->imagickCircleCrop->crop(
                            $tile,
                            $this->starRadius,
                            $this->starX,
                            $this->starY
                        );
                    }

                    $imagick->compositeImage(
                        $tile->getImagick(),
                        \Imagick::COMPOSITE_DEFAULT,
                        $posX,
                        $posY + $annotationHeight
                    );

                    $draw = new \ImagickDraw();
                    $draw->setFillColor(new \ImagickPixel("white"));
                    $draw->setFontWeight(100);
                    $draw->setStrokeWidth(0);

                    $imagick->annotateImage(
                        $draw,
                        $posX + 4,
                        $posY + ($annotationHeight / 2),
                        0,
                        "M: "
                            . round($autofocusResult->getPoints()[$i]->getMeasure(), 4)
                            . "\n"
                            . "P: "
                            . $autofocusResult->getPoints()[$i]->getPosition()
                    );

                    if ($autofocusResult->getPoints()[$i]->getPosition() == $autofocusResult->getMaximum()->getPosition()) {
                        $draw = new \ImagickDraw();
                        $strokeColor = new \ImagickPixel('red');
                        $draw->setFillOpacity(0);
                        $draw->setStrokeColor($strokeColor);
                        $draw->setStrokeWidth(1);

                        $draw->rectangle(
                            $posX,
                            $posY,
                            $posX + $tileWidth - 1,
                            $posY + $tileHeight + $annotationHeight - 1
                        );

                        $imagick->drawImage($draw);
                    }

                }

                $i++;
            }
        }

        return new ImagickImage($imagick);
    }
}

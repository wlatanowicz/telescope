<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Data\ImagickImage;

class AutoFocusReport
{
    public function generateReport(AutofocusResult $autofocusResult): ImagickImage
    {
        $tileWidth = $autofocusResult->getMaximum()->getImage()->getWidth();
        $tileHeight = $autofocusResult->getMaximum()->getImage()->getHeight();
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
                    $imagick->compositeImage(
                        $autofocusResult->getPoints()[$i]->getImage()->getImagick(),
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

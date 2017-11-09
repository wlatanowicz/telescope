<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class AutofocusPoint
{
    /**
     * @var int
     */
    private $position;

    /**
     * @var float
     */
    private $measure;

    /**
     * @var ImagickImage
     */
    private $image;

    /**
     * AutofocusPoint constructor.
     * @param int $position
     * @param float $measure
     * @param $image
     */
    public function __construct(int $position, float $measure, BinaryImages $image)
    {
        $this->position = $position;
        $this->measure = $measure;
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return float
     */
    public function getMeasure(): float
    {
        return $this->measure;
    }

    /**
     * @return mixed
     */
    public function getImage(): BinaryImages
    {
        return $this->image;
    }

}

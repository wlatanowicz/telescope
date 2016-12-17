<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class StarDimensions
{
    /**
     * @var float
     */
    private $height;

    /**
     * @var float
     */
    private $width;

    /**
     * StarDimensions constructor.
     * @param float $width
     * @param float $height
     */
    public function __construct($width, $height)
    {
        $this->height = $height;
        $this->width = $width;
    }
}

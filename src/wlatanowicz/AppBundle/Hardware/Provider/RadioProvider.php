<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\RadioInterface;

class RadioProvider
{
    /**
     * @var RadioInterface[]
     */
    private $radios;

    /**
     * RadioProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\RadioInterface[] $radios
     */
    public function __construct(array $radios)
    {
        $this->radios = $radios;
    }

    public function getRadio(string $name): RadioInterface
    {
        return $this->radios[$name];
    }
}

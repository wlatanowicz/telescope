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
     * @var string|null
     */
    private $default;

    /**
     * RadioProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\RadioInterface[] $radios
     * @param string|null $default
     */
    public function __construct(array $radios, string $default = null)
    {
        $this->radios = $radios;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return RadioInterface
     */
    public function getRadio(string $name = null): RadioInterface
    {
        return $this->radios[$name ?? $this->default];
    }
}

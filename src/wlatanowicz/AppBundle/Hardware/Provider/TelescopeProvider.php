<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class TelescopeProvider
{
    /**
     * @var TelescopeInterface[]
     */
    private $telescopes;

    /**
     * TelescopeProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\TelescopeInterface[] $telescopes
     */
    public function __construct(array $telescopes)
    {
        $this->telescopes = $telescopes;
    }

    public function getTelescope(string $name): TelescopeInterface
    {
        return $this->telescopes[$name];
    }
}

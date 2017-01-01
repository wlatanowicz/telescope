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
     * @var string|null
     */
    private $default;

    /**
     * TelescopeProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\TelescopeInterface[] $telescopes
     * @param string|null $default
     */
    public function __construct(array $telescopes, string $default = null)
    {
        $this->telescopes = $telescopes;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return TelescopeInterface
     */
    public function getTelescope(string $name = null): TelescopeInterface
    {
        return $this->telescopes[$name ?? $this->default];
    }
}

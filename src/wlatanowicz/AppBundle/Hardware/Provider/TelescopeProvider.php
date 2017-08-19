<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class TelescopeProvider implements ProviderInterface
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

    public function getAvailableValues(): array
    {
        return array_keys($this->telescopes);
    }

    public function getDefaultValue(): string
    {
        return $this->default;
    }
}

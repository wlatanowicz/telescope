<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class FocuserProvider implements ProviderInterface
{
    /**
     * @var FocuserInterface[]
     */
    private $focusers;

    /**
     * @var string|null
     */
    private $default;

    /**
     * FocuserProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\FocuserInterface[] $focusers
     * @param string|null $default
     */
    public function __construct(array $focusers, string $default = null)
    {
        $this->focusers = $focusers;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return FocuserInterface
     */
    public function getFocuser(string $name = null): FocuserInterface
    {
        return $this->focusers[$name ?? $this->default];
    }

    public function getAvailableValues(): array
    {
        return array_keys($this->focusers);
    }

    public function getDefaultValue(): string
    {
        return $this->default;
    }
}

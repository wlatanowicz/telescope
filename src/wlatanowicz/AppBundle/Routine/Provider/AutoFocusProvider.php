<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\Provider;

use wlatanowicz\AppBundle\Hardware\Provider\ProviderInterface;
use wlatanowicz\AppBundle\Routine\AutoFocus\AutoFocusInterface;

class AutoFocusProvider implements ProviderInterface
{
    /**
     * @var AutoFocusInterface[]
     */
    private $autoFocuses;

    /**
     * @var string|null
     */
    private $default;

    /**
     * AutoFocus constructor.
     * @param array $autoFocuses
     * @param string|null $default
     */
    public function __construct(array $autoFocuses, string $default = null)
    {
        $this->autoFocuses = $autoFocuses;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return AutoFocusInterface
     */
    public function getAutoFocus(string $name = null): AutoFocusInterface
    {
        return $this->autoFocuses[$name ?? $this->default];
    }

    public function getAvailableValues(): array
    {
        return array_keys($this->autoFocuses);
    }

    public function getDefaultValue(): string
    {
        return $this->default;
    }
}

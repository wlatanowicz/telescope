<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\Provider;

use wlatanowicz\AppBundle\Hardware\Provider\ProviderInterface;
use wlatanowicz\AppBundle\Routine\MeasureInterface;

class MeasureProvider implements ProviderInterface
{
    /**
     * @var MeasureInterface[]
     */
    private $measures;

    /**
     * @var string|null
     */
    private $default;

    /**
     * Measure constructor.
     * @param array $measures
     * @param string|null $default
     */
    public function __construct(array $measures, string $default = null)
    {
        $this->measures = $measures;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return MeasureInterface
     */
    public function getMeasure(string $name = null): MeasureInterface
    {
        return $this->measures[$name ?? $this->default];
    }

    public function getAvailableValues(): array
    {
        return array_keys($this->measures);
    }

    public function getDefaultValue(): string
    {
        return $this->default;
    }
}

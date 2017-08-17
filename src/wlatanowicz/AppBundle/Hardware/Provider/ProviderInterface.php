<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

interface ProviderInterface
{
    /**
     * @return string[]
     */
    public function getAvailableValues(): array;
}

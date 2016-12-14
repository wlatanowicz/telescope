<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class FocuserProvider
{
    /**
     * @var FocuserInterface[]
     */
    private $focusers;

    /**
     * FocuserProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\FocuserInterface[] $focusers
     */
    public function __construct(array $focusers)
    {
        $this->focusers = $focusers;
    }

    public function getFocuser(string $name): FocuserInterface
    {
        return $this->focusers[$name];
    }
}

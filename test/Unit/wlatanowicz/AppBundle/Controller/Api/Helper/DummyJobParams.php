<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Api\Helper;

use wlatanowicz\AppBundle\Job\Params\JobParamsInterface;

class DummyJobParams implements JobParamsInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $int;

    /**
     * DummyJobParams constructor.
     * @param string $string
     * @param int $int
     */
    public function __construct(string $string, int $int)
    {
        $this->string = $string;
        $this->int = $int;
    }

}

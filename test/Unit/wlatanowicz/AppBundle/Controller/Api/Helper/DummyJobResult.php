<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Api\Helper;

use JMS\Serializer\Annotation\Type;

class DummyJobResult
{
    /**
     * @var int
     * @Type("int")
     */
    private $int;

    /**
     * @var string
     * @Type("string")
     */
    private $string;

    /**
     * DummyJobResult constructor.
     * @param int $int
     * @param string $string
     */
    public function __construct(int $int, string $string)
    {
        $this->int = $int;
        $this->string = $string;
    }
}

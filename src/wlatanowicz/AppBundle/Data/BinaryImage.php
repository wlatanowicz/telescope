<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class BinaryImage
{
    /**
     * @var string
     */
    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }
}

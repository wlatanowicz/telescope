<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Type;

class BinaryImage
{
    /**
     * @var string
     * @Accessor(getter="getDataBase64",setter="setDataBase64")
     * @Type("string")
     */
    private $data;

    /**
     * @var string|null
     * @Type("string")
     */
    private $mimetype;

    public function __construct(string $data, string $mimetype = null)
    {
        $this->data = $data;
        $this->mimetype = $mimetype;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function getDataBase64(): string
    {
        return base64_encode($this->data);
    }

    public function setDataBase64(string $base64)
    {
        $this->data = base64_decode($base64);
    }
}

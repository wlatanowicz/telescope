<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class BinaryImage
{
    const MIMETYPE_JPEG = 'image/jpeg';
    const MIMETYPE_RAW = 'image/x-sony-arw';

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
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
}

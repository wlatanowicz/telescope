<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude

class BinaryImage
{
    const MIMETYPE_BIN = 'application/octet-stream';
    const MIMETYPE_JPEG = 'image/jpeg';
    const MIMETYPE_SONY_RAW = 'image/x-sony-arw';

    /**
     * @var array
     * @Exclude
     */
    private static $EXTENSIONS = [
        self::MIMETYPE_BIN => 'bin',
        self::MIMETYPE_JPEG => 'jpeg',
        self::MIMETYPE_SONY_RAW => 'arw',
    ];

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
    public function getMimetype(): string
    {
        if ($this->mimetype === null)
        {
            $this->mimetype = $this->autodetectMimeType();
        }
        return $this->mimetype;
    }

    public function getFileExtension(): string
    {
        return self::$EXTENSIONS[$this->getMimetype()];
    }

    public function getDataBase64(): string
    {
        return base64_encode($this->data);
    }

    public function setDataBase64(string $base64)
    {
        $this->data = base64_decode($base64);
    }

    private function autodetectMimeType()
    {
        if (substr($this->data, 6, 4) === 'JFIF')
        {
            return self::MIMETYPE_JPEG;
        }

        if (substr($this->data, 0, 3) === 'II*')
        {
            return self::MIMETYPE_SONY_RAW;
        }

        return self::MIMETYPE_BIN;
    }
}

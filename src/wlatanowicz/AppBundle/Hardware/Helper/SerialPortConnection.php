<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

class SerialPortConnection
{
    /**
     * @var resource
     */
    private $resource;

    public function __construct(string $path, string $mode)
    {
        $this->resource = fopen($path, $mode);
    }

    public function write(string $data)
    {
        fwrite($this->resource, $data);
    }

    public function read(int $byteCount = 1): string
    {
        return fread($this->resource, $byteCount);
    }

    public function __destruct()
    {
        fclose($this->resource);
    }
}

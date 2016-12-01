<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

class SerialPort
{
    /**
     * @var string
     */
    private $dev;

    public function __construct(string $dev)
    {
        $this->dev = $dev;
    }

    public function connect(): SerialPortConnection
    {
        return new SerialPortConnection($this->dev, "r+");
    }
}

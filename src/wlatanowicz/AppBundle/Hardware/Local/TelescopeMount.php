<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Hardware\Helper\SerialPort;
use wlatanowicz\AppBundle\Hardware\Helper\SerialPortConnection;

class TelescopeMount
{
    /**
     * @var SerialPort
     */
    private $serialPort;

    public function __construct(SerialPort $device)
    {
        $this->serialPort = $device;
    }

    public function sendCommand(string $command): string
    {
        $connection = $this->serialPort->connect();
        $this->writeCommand($connection, $command);
        $response = $this->readResponse($fd);
        fclose($fd);
        return $response;
    }

    private function writeCommand(SerialPortConnection $connection, string $command)
    {
        $connection->write($command);
    }

    private function readResponse(SerialPortConnection $connection): string
    {
        $response = '';
        do {
            $byte = $connection->read();
            $response .= $byte;
        } while ($byte != '#');
        return substr($response, 0, -1);
    }
}

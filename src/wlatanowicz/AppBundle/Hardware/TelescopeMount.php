<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

class TelescopeMount
{
    /**
     * @var string
     */
    private $device;

    /**
     * TelescopeMount constructor.
     * @param string $device
     */
    public function __construct($device)
    {
        $this->device = $device;
    }

    public function sendCommand(string $command): string
    {
        $fd = fopen($this->device, "w+");
        $this->writeCommand($fd, $command);
        $response = $this->readResponse($fd);
        fclose($fd);
        return $response;
    }

    private function writeCommand($fd, string $command)
    {
        echo "WRITE: {$command}\n";
        fwrite($fd, $command);
    }

    private function readResponse($fd): string
    {
        echo "READ: ";
        $response = '';
        do {
            $byte = fread($fd, 1);
            echo $byte;
            $response .= $byte;
        } while ($byte != '#');
        echo "\n";
        return substr($response, 0, -1);
    }
}

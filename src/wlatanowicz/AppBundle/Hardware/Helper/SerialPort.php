<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

class SerialPort
{
    /**
     * @var string
     */
    private $dev;

    /**
     * @var Process
     */
    private $process;

    public function __construct(Process $process, string $dev)
    {
        $this->dev = $dev;
        $this->process = $process;
    }

    public function connect(): SerialPortConnection
    {
        $setupCmd = "stty -F {$this->dev} cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts";
        $this->process->exec($setupCmd);
        return new SerialPortConnection($this->dev, "r+");
    }
}

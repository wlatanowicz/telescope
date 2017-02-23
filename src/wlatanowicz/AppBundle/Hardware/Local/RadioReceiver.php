<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Hardware\Local\Exception\RadioReceiverException;
use wlatanowicz\AppBundle\Hardware\Helper\Process;

class RadioReceiver
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var int
     */
    private $receiverId;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var int
     */
    private $gain;

    public function __construct(Process $process, string $bin, int $receiverId, int $gain = 0)
    {
        $this->process = $process;
        $this->bin = $bin;
        $this->receiverId = $receiverId;
        $this->gain = $gain;
    }

    public function getPowerSpectrum(
        string $minFreq,
        string $maxFreq,
        string $binSize,
        int $integrationTime
    ) : array {
        $cmd = "{$this->bin} -f {$minFreq}:{$maxFreq}:{$binSize} -i {$integrationTime} -g {$this->gain} -1 2> /dev/null";
        echo "$cmd\n";
        return $this->process->exec($cmd);
    }
}

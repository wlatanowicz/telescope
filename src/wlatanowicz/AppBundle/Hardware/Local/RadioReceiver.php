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

    public function __construct(Process $process, string $bin, int $receiverId)
    {
        $this->process = $process;
        $this->bin = $bin;
        $this->receiverId = $receiverId;
    }

    public function getPowerSpectrum(
        string $minFreq,
        string $maxFreq,
        string $binSize,
        int $integrationTime
    ) : array {
        $cmd = "{$this->bin} -f {$minFreq}:{$maxFreq}:{$binSize} -i {$integrationTime} -1";
        return $this->process->exec($cmd);
    }
}

<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

use wlatanowicz\AppBundle\Hardware\Helper\Exception\ProcessException;

class Process
{
    public function exec(string $command): array
    {
        $result = null;
        $code = 0;

        exec($command, $result, $code);

        if ($code !== 0) {
            throw new ProcessException($command, $code);
        }

        return $result;
    }
}

<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper\Exception;

class ProcessException extends \Exception
{
    public static function commandError(string $cmd, int $code): self
    {
        return new self(
            "Error running command: " . $cmd,
            $code
        );
    }
}

<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class RPCResponse
{
    /**
     * @var string
     */
    private $jsonrpc;

    /**
     * @var mixed|null
     */
    private $error;

    /**
     * @var mixed|null
     */
    private $result;

    /**
     * JobResult constructor.
     * @param mixed|null $error
     * @param mixed|null $result
     */
    private function __construct($error = null, $result = null)
    {
        $this->jsonrpc = "2.0";
        $this->error = $error;
        $this->result = $result;
    }

    public static function initWithResult($result): self
    {
        return new self(null, $result);
    }

    public static function initWithError($error): self
    {
        return new self($error, null);
    }
}

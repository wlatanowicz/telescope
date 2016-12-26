<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use Psr\Log\LoggerInterface;

class LoggerWithContext implements LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $context;

    /**
     * LoggerWithContexr constructor.
     * @param LoggerInterface $logger
     * @param array $context
     */
    public function __construct(LoggerInterface $logger, array $context = [])
    {
        $this->logger = $logger;
        $this->context = $context;
    }

    public function emergency($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->emergency($message, $context);
    }

    public function alert($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->alert($message, $context);
    }

    public function critical($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->critical($message, $context);
    }

    public function error($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->error($message, $context);
    }

    public function warning($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->warning($message, $context);
    }

    public function notice($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->notice($message, $context);
    }

    public function info($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->info($message, $context);
    }

    public function debug($message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->debug($message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $context = array_merge($this->context, $context);
        $this->logger->log($level, $message, $context);
    }
}

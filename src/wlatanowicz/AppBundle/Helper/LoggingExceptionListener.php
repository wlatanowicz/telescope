<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class LoggingExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DeliveryAppointmentExceptionListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $exceptionMessage = $exception->getMessage();
        $this->logger->critical($exceptionMessage, ['exception' => $exception]);

        $json = \json_encode(
            [
                "status" => "Error",
                "message" => $exceptionMessage,
                "stack_trace" => $exception->getTrace()
            ],
            JSON_PRETTY_PRINT
        );

        $response = new JsonResponse(
            $json,
            500,
            [],
            true
        );
        // setup the Response object based on the caught exception
        $event->setResponse($response);
    }
}

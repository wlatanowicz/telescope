<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class Focuser implements FocuserInterface
{
    const SETTLE_CHECK_WAIT = 5000000; // 5 sec
    const SETTLE_WAIT = 1000000; // 1 sec

    const WARN_AFTER = 60; //60 sec

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Focuser constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = null
    ) {
        if ($tolerance === null)
        {
            $tolerance = 0;
        }

        $this->logger->info(
            "Setting position (target={target})",
            [
                "target" => $position,
            ]
        );

        $options = [
            "body" => \json_encode(["targetPosition" => $position])
        ];
        $responseRaw = $this->client->request("POST", "/", $options);
        $response = \json_decode($responseRaw->getBody()->getContents(), true);
        if ($response['result'] != 'OK') {
            throw new \Exception();
        }

        $start = time();

        if ($wait) {
            do {
                usleep(self::SETTLE_CHECK_WAIT);

                $currentPosition = $this->getPosition();

                $diff = $position - $currentPosition;

                $positioned = abs($diff) <= abs($tolerance);

                if ((time()-$start) > self::WARN_AFTER) {
                    $this->logger->warning(
                        "Position still not set (target={target}, current={current})",
                        [
                            "target" => $position,
                            "current" => $currentPosition,
                        ]
                    );
                } else {
                    $this->logger->debug(
                        "Waiting for target position (target={target}, current={current})",
                        [
                            "target" => $position,
                            "current" => $currentPosition,
                        ]
                    );
                }

            }while(!$positioned);

            usleep(self::SETTLE_WAIT);
        }

        $this->logger->info(
            "Position set (position={position})",
            [
                "position" => $position,
            ]
        );
    }

    public function getPosition(): int
    {
        $this->logger->info("Getting position");

        $responseRaw = $this->client->request("GET", "/");
        $response = \json_decode($responseRaw->getBody()->getContents(), true);
        if ($response['result'] != 'OK') {
            throw new \Exception();
        }

        $currentPosition = (int)$response['position'];

        $this->logger->info(
            "Got position (position={position})",
            [
                "position" => $currentPosition,
            ]
        );

        return $currentPosition;
    }

}

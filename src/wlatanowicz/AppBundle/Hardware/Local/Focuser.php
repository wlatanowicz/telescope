<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use GuzzleHttp\ClientInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class Focuser implements FocuserInterface
{
    const SETTLE_CHECK_WAIT = 5000000; // 5 sec
    const SETTLE_WAIT = 1000000; // 1 sec

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Focuser constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function setPosition(
        float $position,
        bool $wait = true,
        float $tolerance = 5
    ) {
        $options = [
            "body" => \json_encode(["targetPosition" => round($position)])
        ];
        $responseRaw = $this->client->request("POST", "/", $options);
        $response = \json_decode($responseRaw->getBody()->getContents(), true);
        if ($response['result'] != 'OK') {
            throw new \Exception();
        }

        if ($wait) {
            do {
                usleep(self::SETTLE_CHECK_WAIT);

                $currentPosition = $this->getPosition();

                $diff = $position - $currentPosition;

                $positioned = abs($diff) <= abs($tolerance);

            }while(!$positioned);

            usleep(self::SETTLE_WAIT);
        }
    }

    public function getPosition(): float
    {
        $responseRaw = $this->client->request("GET", "/");
        $response = \json_decode($responseRaw->getBody()->getContents(), true);
        if ($response['result'] != 'OK') {
            throw new \Exception();
        }

        return (int)$response['position'];
    }

}

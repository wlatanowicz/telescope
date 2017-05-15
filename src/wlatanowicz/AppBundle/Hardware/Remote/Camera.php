<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Remote;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;

class Camera implements CameraInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Camera constructor.
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ClientInterface $client,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->serializer = $serializer;

        $this->logger = $logger;
    }

    public function exposure(float $time): BinaryImage
    {
        $this->logger->info(
            "Starting exposure (time={time}s)",
            [
                "time" => $time,
            ]
        );

        $query = [
            'time' => $time,
        ];
        $json = $this->client->request('GET', 'image?' . http_build_query($query) )->getBody()->getContents();

        /**
         * @var $object BinaryImage
         */
        $object = $this->serializer->deserialize(
            $json,
            BinaryImage::class,
            'json'
        );

        $this->logger->info("Finished exposure");

        return $object;
    }

    public function setIso(int $iso)
    {
        $options = [
            'body' => $iso,
        ];
        $this->client->request('POST', 'iso', $options );
    }

    public function setFormat(string $format)
    {
        $options = [
            'body' => json_encode($format),
        ];
        $this->client->request('POST', 'format', $options );
    }

    public function getIso(): int
    {
        $response = $this->client->request('GET', 'iso' )->getBody()->getContents();
        return intval(\json_decode($response), 10);
    }

    public function getFormat(): string
    {
        $response = $this->client->request('GET', 'format' )->getBody()->getContents();
        return \json_decode($response);
    }

    public function getBatteryLevel(): float
    {
        $response = $this->client->request('GET', 'batterylevel' )->getBody()->getContents();
        return \json_decode($response);
    }
}

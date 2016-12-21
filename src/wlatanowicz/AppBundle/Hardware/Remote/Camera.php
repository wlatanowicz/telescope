<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Remote;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
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
     * Camera constructor.
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function exposure(int $time): BinaryImage
    {
        $query = [
            'time' => $time,
        ];
        $json = $this->client->request('GET', 'image?' . http_build_query($query) );

        /**
         * @var $object BinaryImage
         */
        $object = $this->serializer->deserialize(
            $json,
            BinaryImage::class,
            'json'
        );

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

}

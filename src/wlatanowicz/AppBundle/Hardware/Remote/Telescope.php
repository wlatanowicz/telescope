<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Remote;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class Telescope implements TelescopeInterface
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

    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    ) {
        $query = [
            'wait' => $wait ? 'true' : 'false',
        ];

        if ($tolerance !== null) {
            $query['decTolerance'] = $tolerance->getDeclination();
            $query['raTolerance'] = $tolerance->getRightAscension();
        }

        $options = [
            'body' => $this->serializer->serialize($coordinates, 'json'),
        ];

        $this->client->request('POST', 'position?' . http_build_query($query), $options );
    }

    public function getPosition(): Coordinates
    {
        $json = $this->client->request('GET', 'position' );

        /**
         * @var $coordinates Coordinates
         */
        $coordinates = $this->serializer->deserialize(
            $json,
            Coordinates::class,
            'json'
        );
        return $coordinates;
    }

}

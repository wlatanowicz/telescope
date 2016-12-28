<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Telescope;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;

class Position
{
    /**
     * @var TelescopeProvider
     */
    private $telescopeProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Position constructor.
     * @param TelescopeProvider $telescopeProvider
     * @param SerializerInterface $serializer
     */
    public function __construct(TelescopeProvider $telescopeProvider, SerializerInterface $serializer)
    {
        $this->telescopeProvider = $telescopeProvider;
        $this->serializer = $serializer;
    }

    public function getPosition(string $name): Response
    {
        $telescope = $this->telescopeProvider->getTelescope($name);
        $position = $telescope->getPosition();

        $json = $this->serializer->serialize($position, 'json');
        return new JsonResponse($json, 200, [], true);
    }

    public function setPosition(string $name, Request $request): Response
    {
        $json = $request->getContent();
        $position = $this->serializer->deserialize(
            $json,
            Coordinates::class,
            'json'
        );

        $tolerance = null;
        if ($request->query->has("decTolerance")
            && $request->query->has("raTolerance")){
            $tolerance = new Coordinates(
                floatval($request->query->get("raTolerance")),
                floatval($request->query->has("decTolerance"))
            );
        }

        $telescope = $this->telescopeProvider->getTelescope($name);
        $telescope->setPosition(
            $position,
            true,
            $tolerance
        );

        $json = $this->serializer->serialize($position, 'json');
        return new JsonResponse($json, 200, [], true);
    }

}

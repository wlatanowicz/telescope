<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Controller\Hardware;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use wlatanowicz\AppBundle\Hardware\Provider\ProviderInterface;

class Available
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Available constructor.
     * @param ProviderInterface[] $providers
     * @param SerializerInterface $serializer
     */
    public function __construct(array $providers, SerializerInterface $serializer)
    {
        $this->providers = $providers;
        $this->serializer = $serializer;
    }

    public function getAvailable(string $deviceKind)
    {
        $json = $this->serializer->serialize(
            $this->providers[$deviceKind]->getAvailableValues(),
            'json'
        );
        return new JsonResponse($json, 200, [], true);
    }

    public function getDefault(string $deviceKind)
    {
        $json = $this->serializer->serialize(
            $this->providers[$deviceKind]->getDefaultValue(),
            'json'
        );
        return new JsonResponse($json, 200, [], true);
    }
}

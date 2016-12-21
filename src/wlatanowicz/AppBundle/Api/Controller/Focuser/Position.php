<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Focuser;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;

class Position
{
    /**
     * @var FocuserProvider
     */
    private $focuserProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Image constructor.
     * @param FocuserProvider $focuserProvider
     */
    public function __construct(
        FocuserProvider $focuserProvider,
        SerializerInterface $serializer
    ) {
        $this->focuserProvider = $focuserProvider;
        $this->serializer = $serializer;
    }

    public function getPosition(string $name): Response
    {
        $focuser = $this->focuserProvider->getFocuser($name);
        $position = $focuser->getPosition();

        return new JsonResponse($position, 200, [], true);
    }

    public function setPosition(string $name, Request $request): Response
    {
        $position = intval($request->getContent());
        $focuser = $this->focuserProvider->getFocuser($name);
        $focuser->setPosition($position);

        return new JsonResponse($position, 200, [], true);
    }
}

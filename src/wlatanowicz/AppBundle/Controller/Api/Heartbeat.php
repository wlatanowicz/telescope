<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

class Heartbeat
{
    public function getHeartbeat()
    {
        return new JsonResponse([
            "status" => "Alive",
            "time" => date("Y-m-d H:i:s"),
        ]);
    }
}

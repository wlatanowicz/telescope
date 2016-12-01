<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class Info
{
    public function getInfo()
    {
        return new JsonResponse([
            "message" => "hi",
            "time" => date("Y-m-d H:i:s"),
        ]);
    }
}

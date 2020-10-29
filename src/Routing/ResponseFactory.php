<?php

declare(strict_types=1);

namespace FakeFakers\ApiComponents\Routing;

use \Illuminate\Http\JsonResponse;
use \Illuminate\Routing\ResponseFactory as BaseResponseFactory;

/**
 * Class ResponseFactory
 * @package Brainex\ApiComponents\Routing
 *
 * JSON Response options hook
 */
class ResponseFactory extends BaseResponseFactory
{
    public function json(
        $data = [],
        $status = 200,
        array $headers = [],
        $options = RoutingServiceProvider::JSON_OPTIONS
    ): JsonResponse {
        return parent::json($data, $status, $headers, $options);
    }
}
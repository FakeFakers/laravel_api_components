<?php
/**
 * File: Router.php
 * Author: Dmitry K. <dmitry.k@brainex.co>
 * Date: 2018-08-21
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace Brainex\ApiComponents\Routing;

use ArrayObject;
use JsonSerializable;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class Router
 * @package Brainex\ApiComponents\Routing
 *
 * Hook JsonResponse
 */
class Router extends \Illuminate\Routing\Router
{
    /**
     * Static version of prepareResponse.
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @param  mixed $response
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public static function toResponse($request, $response)
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif ($response instanceof Model && $response->wasRecentlyCreated) {
            $response = new JsonResponse($response, 201, [], RoutingServiceProvider::JSON_OPTIONS);
        } elseif (!$response instanceof SymfonyResponse &&
            ($response instanceof Arrayable ||
                $response instanceof Jsonable ||
                $response instanceof ArrayObject ||
                $response instanceof JsonSerializable ||
                \is_array($response))) {
            $response = new JsonResponse($response, 200, [], RoutingServiceProvider::JSON_OPTIONS);
        } elseif (!$response instanceof SymfonyResponse) {
            $response = new Response($response);
        }

        if ($response->getStatusCode() === Response::HTTP_NOT_MODIFIED) {
            $response->setNotModified();
        }

        return $response->prepare($request);
    }
}
<?php

declare(strict_types=1);

namespace FakeFakers\ApiComponents\Exceptions;

use Illuminate\Http\JsonResponse;

/**
 * Interface JsonRenderable
 * @package FakeFakers\ApiComponents\Exceptions
 *
 * Adding ability to render exception as json
 */
interface JsonRenderable
{
    /**
     * Render exception as JsonResponse
     *
     * @return JsonResponse
     */
    public function renderAsJson(): JsonResponse;

}
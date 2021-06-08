<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Unauthorized response
     *
     * @return JsonResponse
     */
    public function unauthorizedResponse()
    {
        return response()->json([
            'error' => 'Unauthorized',
            'code' => Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);
    }
}

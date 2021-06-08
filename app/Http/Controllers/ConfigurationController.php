<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{
    public function getConfigurations()
    {
        $configs = Configuration::all()->pluck('value', 'key');

        return new JsonResponse($configs, 200);
    }
}

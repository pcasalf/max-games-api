<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlatformRequest;
use App\Http\Requests\EditPlatformRequest;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlatformController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function getPlatforms(): AnonymousResourceCollection
    {
        $platforms = Platform::query()->paginate(10);

        return PlatformResource::collection($platforms);
    }

    /**
     * @param Platform $platform
     * @return PlatformResource
     */
    public function getPlatform(Platform $platform): PlatformResource
    {
        return new PlatformResource($platform);
    }

    /**
     * @param CreatePlatformRequest $request
     * @return PlatformResource
     */
    public function createPlatform(CreatePlatformRequest $request): PlatformResource
    {
        $platform = new Platform($request->validated());
        $platform->save();

        return new PlatformResource($platform);
    }

    /**
     * @param Platform $platform
     * @param EditPlatformRequest $request
     * @return PlatformResource
     */
    public function editPlatform(Platform $platform, EditPlatformRequest $request): PlatformResource
    {
        $platform->name = $request->get('name');
        $platform->logo = $request->get('logo');

        return new PlatformResource($platform);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request): UserResource
    {
        /** @var User $user */
        $user = $request->get('keyable');

        return new UserResource($user);
    }

    /**
     * Make user login
     *
     * @param AuthLoginRequest $request
     * @return mixed
     */
    public function login(AuthLoginRequest $request)
    {
        $user = User::query()->where('email', $request->get('email'))->first();

        if($user instanceof User) {
            $passwordMatch = Hash::check(
                $request->get('password'),
                $user->password
            );

            if($passwordMatch) {
                $user->createApiKey();
                return new UserResource($user->load(['apiKeys']));
            }

            return $this->unauthorizedResponse();
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function logout(Request $request)
    {
        $user = request()->get('keyable');

        if($user instanceof User) {
            $user->apiKeys()->first()->delete();

            return response('', 204);
        }
    }

    /**
     * Register
     *
     * @param AuthRegisterRequest $request
     * @return mixed
     */
    public function register(AuthRegisterRequest $request)
    {
       DB::beginTransaction();

       try {
            $user = new User();
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->name = $request->get('name');
            $user->verification_token = $user->generateVerificationToken();
            $user->last_name = $request->get('last_name');
            $user->birthday = $request->get('birthday');

            $user->save();

            DB::commit();

            return new UserResource($user);
       } catch (QueryException $e) {
           DB::rollBack();
           return response()->json([
                'error' => 'Ha ocurrido un error al realizar el registro',
                'code' => Response::HTTP_BAD_REQUEST
           ], Response::HTTP_BAD_REQUEST);
       }
    }

    /**
     * Verify user
     *
     * @param Request $request
     * @return mixed
     */
    public function verify(Request $request)
    {
        $user = User::query()
            ->where('verification_token', $request->get('token'))
            ->first();

        if($user instanceof User && !$user->verified_at) {
            $user->verified_at = Carbon::now();
            $user->save();
            return new UserResource($user);
        }

        return response()->json(['Usuario no encontrado'], Response::HTTP_NOT_FOUND);
    }
}

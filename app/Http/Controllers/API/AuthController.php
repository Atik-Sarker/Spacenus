<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


     /**
     * User Register
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8'
        ]);
        if ($validator->fails()) {
            $response = array("status" => false, "errors" => $validator->errors(), "data" =>  request()->all());
            return $this->errors(422, $response);
        }
        //Retrieve the validated input...
        $data = $validator->validated();

        try{
            User::create($data);
            Log::info("User Created from API");
            return $this->success(201,'User registered successfully', true, $data);
        }catch(\Exception $e){
            Log::error('Failed to create User form API :'.$e->getMessage());
            $response = array("status" => false, "errors" => 'Failed to create :'.$e->getMessage(), "data" =>  request()->all());
            return $this->errors($e->getCode(), $response);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {

            Log::error('Failed to login');
            return $this->errors(401, 'Unauthorized', false);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(200,'Success', true, auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success(
            200,
            'User logged in successfully.',
             true,
             [
            'user' => ['name' => auth("api")->user()->name, 'email' => auth("api")->user()->email],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        );
    }
}

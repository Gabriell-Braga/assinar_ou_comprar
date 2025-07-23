<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Autenticação",
 * )
 */

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['postLogin']
        ]);
    }

    
    /**
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="authLogin",
     *      tags={"Auth"},
     *      summary="Autenticação",
     *      description="Autenticação",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postLogin()
    {
        $credentials = request(['email', 'password']);
        $token = false;

        if ($user = \App\Models\User::where('email', $credentials['email'])->first()) {
            if (\Hash::check($credentials['password'], $user->password)) {
                $ttl = \App\Models\Setting::getValue('jwt.ttl', 90);
                $token = auth()->setTTL($ttl)->login($user);
            }
        }

        if (! $token) {
            throw new \Exception('Autenticação inválida');
        }

        return $this->respondWithToken($token);
    }


    
    /**
     * @OA\Post(
     *      path="/auth/me",
     *      operationId="authMe",
     *      tags={"Auth"},
     *      summary="Dados do usuário logado",
     *      description="Dados do usuário logado",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postMe()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *      path="/auth/logout",
     *      operationId="authLogout",
     *      tags={"Auth"},
     *      summary="Sair do sistema",
     *      description="Sair do sistema",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postLogout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * @OA\Post(
     *      path="/auth/refresh",
     *      operationId="authRefresh",
     *      tags={"Auth"},
     *      summary="Atualiza token",
     *      description="Atualiza token",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postRefresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    /**
     * @OA\Post(
     *      path="/auth/password-token",
     *      operationId="authPasswordToken",
     *      tags={"Auth"},
     *      summary="Inicia o processo de alteração de senha enviando e-mail com token para usuário",
     *      description="Inicia o processo de alteração de senha enviando e-mail com token para usuário",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postPasswordToken($request) {
        return \App\Models\User::passwordToken($request->all());
    }


    /**
     * @OA\Post(
     *      path="/auth/password-refresh",
     *      operationId="authPasswordRefresh",
     *      tags={"Auth"},
     *      summary="Finaliza processo de alteração de senha recebendo e-mail, token, nova senha e confirmação de nova senha",
     *      description="Finaliza processo de alteração de senha recebendo e-mail, token, nova senha e confirmação de nova senha",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function postPasswordReset($request) {
        return \App\Models\User::passwordReset($request->all());
    }
}

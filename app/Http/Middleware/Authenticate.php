<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\BeforeValidException;
use App\Providers\RouteServiceProvider;
use Firebase\JWT\SignatureInvalidException;
use App\Http\Responses\UnauthorizedResponse;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $this->validateHeader($request);
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => $e->getMessage(),
                'data' => null
            ], 401);
        }
    }

    private function validateHeader(Request $request)
    {
        $key = env('JWT_SECRET', 'default-value');
        $token = $request->bearerToken();
        // check bearer token is valid
        if (!$token) {
            throw new \Exception('Bearer token is required.');
        }

        try {
            $decoded_payload = JWT::decode($token, $key, array('HS256'));
        } catch (SignatureInvalidException $e) {
            throw new \Exception($e->getMessage());
        } catch (BeforeValidException $e) {
            throw new \Exception($e->getMessage());
        } catch (ExpiredException $e) {
            throw new \Exception('Token expired');
        }
    }
}

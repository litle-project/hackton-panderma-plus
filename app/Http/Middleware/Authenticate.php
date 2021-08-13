<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\BeforeValidException;
use App\Providers\RouteServiceProvider;
use Firebase\JWT\SignatureInvalidException;

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

        if (!$token) {
            throw new \Exception('Bearer token is required.');
        }

        try {
            $decoded_payload = JWT::decode($token, $key, array('HS256'));
            $user = User::where('user_id', $decoded_payload->data->id)->first();
            if (!$user) throw new \Exception('User has been deleted');
            // if ($user && !$user->is_verified) throw new \Exception('Your Account is Not Verified');
        } catch (SignatureInvalidException $e) {
            throw new \Exception($e->getMessage());
        } catch (BeforeValidException $e) {
            throw new \Exception($e->getMessage());
        } catch (ExpiredException $e) {
            throw new \Exception('Token expired');
        }
    }
}

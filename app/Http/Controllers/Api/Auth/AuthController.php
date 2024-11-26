<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserAuthLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $userCheck = User::where('username', $request->username)->count();
        if ($userCheck > 0) {
            try {
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                    $user = Auth::user();
                    $user->picture = asset('storage/images/users/' . $user->picture);
                    $token = $user->createToken($user->name);
                    $data = [
                        'permissions' => $user->getAllPermissions()->map(function ($item) {
                            return $item->name;
                        }),
                        'user' => $user,
                    ];
                    return response()->json([
                        'message' => 'Login Berhasil',
                        '_token' => $token->plainTextToken . '.' . base64_encode(json_encode($data))
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Validation failed',
                        'errors' => 'Opps! Password yang anda masukan salah'
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'trace' => $e->getTrace(),
                    'message' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => 'Opps! Username yang anda masukan salah'
            ], 422);
        }
    }




    public function index(Request $request)
    {
        $user = Auth::user();
        $user->picture = asset('storage/images/users/' . $user->picture);
        $token = $user->createToken($user->name);
        $data = [
            'permissions' => $user->getAllPermissions()->map(function ($item) {
                return $item->name;
            }),
            'user' => $user,
        ];

        return response()->json([
            'message' => 'Login Berhasil',
            '_token' => $token->plainTextToken . '.' . base64_encode(json_encode($data))
        ]);
    }

    public function verify()
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'Logged In'
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function logout(Request $request, User $user)
    {
        try {
            if (isset(Auth::user()->token)) {
                $response = Http::withOptions(['verify' => false])->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '  . Auth::user()->token->access_token
                ])->get(config('services.oauth_server.uri') . '/api/logout');
            }

            if(!empty($user->hashid)) {
                Auth::user()->currentAccessToken()->delete();
                $user->picture = asset('storage/images/users/' . $user->picture);
                $token = $user->createToken($user->name);
                $data = [
                    'permissions' => $user->getAllPermissions()->map(function ($item) {
                        return $item->name;
                    }),
                    'user' => $user,
                ];
                return response()->json([
                    'message' => 'Logged in as user '. $user->name,
                    '_token' => $token->plainTextToken . '.' . base64_encode(json_encode($data))
                ]);
            }

            $user = Auth::user();
            $user->currentAccessToken()->delete();

            $queries = http_build_query([
                'redirect_uri' => $request->redirect,
            ]);

            return response()->json([
                'message' => 'Logget Out',
                'logout_sso_uri' => config('services.oauth_server.uri') . '/logout?' . $queries
            ]);
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginAs(User $user)
    {
        try {
            $userLogin = Auth::user();
            $userLogin->currentAccessToken()->delete();
            $user->picture = asset('storage/images/users/' . $user->picture);
            $token = $user->createToken($user->name);
            $data = [
                'permissions' => $user->getAllPermissions()->map(function ($item) {
                    return $item->name;
                }),
                'user' => $user,
            ];
            return response()->json([
                'message' => 'Logged in as user '. $user->name,
                '_token' => $token->plainTextToken . '.' . base64_encode(json_encode($data))
            ]);
        } catch(Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ]);
        }
    }
}

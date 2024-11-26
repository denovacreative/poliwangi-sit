<?php

namespace App\Http\Controllers\Web\OAuth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    const ROLES = [
        'dosen' => 4,
        'mahasiswa' => 999,
        'admin_prodi' => 24,
        'admin_jurusan' => 22,
        'keuangan' => 18,
        'admin' => 7,
        'pengelola_web' => 37
    ];

    public function redirect(Request $request)
    {
        if ($request->has('redirect')) {
            session(['redirect' => $request->redirect]);
        }

        $queries = http_build_query([
            'client_id' => config('services.oauth_server.client_id'),
            'redirect_uri' => config('services.oauth_server.redirect'),
            'response_type' => 'code',
        ]);

        return redirect(config('services.oauth_server.uri') . '/oauth/authorize?' . $queries);
    }

    public function callback(Request $request)
    {
        try {
            $redirect = session('redirect');
            $response = Http::withOptions([
                'verify' => false
            ])->post(config('services.oauth_server.uri') . '/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.oauth_server.client_id'),
                'client_secret' => config('services.oauth_server.client_secret'),
                'redirect_uri' => config('services.oauth_server.redirect'),
                'code' => $request->code
            ]);

            $response = $response->json();
            if ($this->manageSSO($request, $response)) {
                $response = Http::withOptions(['verify' => false])->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '  . $response['access_token']
                ])->get(config('services.oauth_server.uri') . '/api/logout');

                $q = http_build_query([
                    'client_id' => config('services.oauth_server.client_id'),
                    'redirect_uri' => config('services.oauth_server.redirect'),
                    'response_type' => 'code',
                ]);

                $queries = http_build_query([
                    'redirect_uri' => url('oauth/redirect'),
                ]);

                return redirect(config('services.oauth_server.uri') . '/logout?' . $queries);
            }

            if (!isset($response['access_token'])) {
                return redirect('/oauth/redirect?redirect=' . session('redirect'));
            }

            $token = $request->user()->createToken('sso-poliwangi');

            $request->user()->token()->create([
                'access_token' => $response['access_token'],
                'expires_in' => $response['expires_in'],
                'refresh_token' => $response['refresh_token']
            ]);

            $token = base64_encode($token->plainTextToken);
            return redirect()->to("$redirect/sso/$token");
        } catch (\Exception $e) {
            $data = [
                'status' => 'failed',
                'message' => 'Opps! terjadi kesalahan'
            ];
            dd($e->getMessage());
        }
    }

    protected function manageSSO(Request $request, $response)
    {
        if (!isset($response['access_token'])) {
            return redirect('/oauth/redirect?redirect=' . session('redirect'));
        }

        $response = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $response['access_token']
        ])->get(config('services.oauth_server.uri') . '/api/user');

        if ($response->status() == 200) {
            $ssoUser = $response->json();
        } else {
            $this->ssoLogout($request);
        }

        if (isset($ssoUser)) {
            switch ($ssoUser['staff']) {
                case self::ROLES['dosen']:
                    $user = $this->loginEmployeeRoles($request, $ssoUser, 'Dosen');
                    break;
                case self::ROLES['mahasiswa']:
                    $user = $this->loginCollegeStudentRoles($request, $ssoUser);
                    break;
                case self::ROLES['admin_prodi']:
                    $user = $this->loginEmployeeRoles($request, $ssoUser, 'Admin Program Studi');
                    break;
                case self::ROLES['keuangan']:
                    $user = $this->loginEmployeeRoles($request, $ssoUser, 'Keuangan');
                    break;
                case self::ROLES['admin']:
                    $user = $this->loginEmployeeRoles($request, $ssoUser, 'Admin');
                    break;
                case self::ROLES['admin_jurusan']:
                    $user = $this->loginEmployeeRoles($request, $ssoUser, 'Admin Jurusan');
                    break;
                case self::ROLES['pengelola_web']:
                    $user = User::whereUsername('root')->first();
                    break;
                default:
                    $user = User::whereUsername('default')->first();
            }

            if (is_null($user)) {
                return true;
            } else {
                Auth::login($user);
                $request->user()->token()->delete();
                return false;
            }
        }
    }

    public function loginEmployeeRoles(Request $request, $response, $role)
    {
        $checkUser = User::whereUsername($response['username']);

        if ($checkUser->count() <= 0) {
            $checkEmployee = Employee::where('name', 'iLike', $response['name']);

            if ($checkEmployee->count() > 0) {
                $employee = $checkEmployee->first();
                $user = User::create([
                    'userable_type' => Employee::class,
                    'userable_id' => $employee->id,
                    'name' => $response['name'],
                    'email' => $response['email'],
                    'username' => $response['username'],
                    'is_active' => true
                ])->assignRole($role);

                return $user;
            } else {
                return null;
            }
        } else {
            return $checkUser->first();
        }
    }

    public function loginCollegeStudentRoles(Request $request, $response)
    {
        $checkUser = User::whereUsername($response['username']);

        if ($checkUser->count() <= 0) {
            $checkStudent = Student::whereNim($response['username']);

            if ($checkStudent->count() > 0) {
                $student = $checkStudent->first();
                $user = User::create([
                    'userable_type' => Student::class,
                    'userable_id' => $student->id,
                    'name' => $response['name'],
                    'email' => $response['email'],
                    'username' => $response['username'],
                    'is_active' => true
                ])->assignRole('Mahasiswa');

                return $user;
            } else {
                return null;
            }
        } else {
            return $checkUser->first();
        }
    }

    public function ssoLogout(Request $request)
    {
        if (Auth::check()) {
            if (isset(Auth::user()->token)) {
                $response = Http::withOptions(['verify' => false])->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '  . Auth::user()->token->access_token
                ])->get(config('services.oauth_server.uri') . '/api/logout');

                Auth::guard()->logout();
            }
        }

        $request->session()->flush();

        $request->session()->regenerate();

        $queries = http_build_query([
            'client_id' => config('services.oauth_server.client_id'),
            'redirect_uri' => config('services.oauth_server.redirect'),
            'response_type' => 'code',
        ]);

        return redirect(config('services.oauth_server.uri') . '/oauth/authorize?' . $queries);
    }
}

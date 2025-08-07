<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CredentialModel;
use App\Models\UserModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class CredentialController extends Controller
{
    use ResponseTrait;

    public function onLogin(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|exists:credentials,email',
            'password' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $authAttempt = Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']]);
            if (!$authAttempt) {
                return $this->dataResponse('error', 200, __('msg.login_failed'));
            }

            $userModel = UserModel::select([
                'email',
                'first_name',
                'last_name',
                'middle_name',
                'prefix',
                'suffix',
                'position',
                'user_access'
            ])
                ->selectRaw("
            TRIM(
                CONCAT(
                    IFNULL(CONCAT(prefix, ' '), ''),
                    IFNULL(CONCAT(first_name, ' '), ''),
                    IF(middle_name IS NOT NULL AND middle_name != '', CONCAT(middle_name, ' '), ''),
                    IFNULL(CONCAT(last_name, ' '), ''),
                    IFNULL(suffix, '')
                )
            ) AS full_name
        ")
                ->where('credential_id', auth()->id())
                ->first();
            $token = auth()->user()->createToken('appToken')->plainTextToken;
            DB::commit();
            $data = [
                'user_details' => $userModel,
                'token' => $token,
            ];
            return $this->dataResponse('success', 200, __('msg.login_success'), $data);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }

    public function onLogout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->dataResponse('success', 200, __('msg.logout'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }


    public function onCheckToken()
    {
        try {
            return response()->json('success');
        } catch (Exception $exception) {
            \Log::info($exception);
            $data = [
                'status' => false,
                'message' => 'Token is invalid'
            ];
            return response()->json($data, 401);
        }
    }

    public function onCreateSignedUrl($credentialQuery, $type, $route)
    {
        try {
            $credentialSignedRoute = $credentialQuery->first();
            if (!$credentialSignedRoute) {
                return $this->dataResponse('error', 404, __('msg.signed_url_invalid'));
            }
            $baseURL = env('BASE_URL');
            $token = $type . '|' . bin2hex(random_bytes(16));
            $temporaryUrl = $baseURL . $route . '/' . $token;
            $credentialSignedRoute->signed_route = $token;
            $credentialSignedRoute->save();
            return $temporaryUrl;
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }

    public function onResetPassword(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
        ]);
        try {
            $credentialToUpdate = CredentialModel::where('email', $fields['email'])->firstOrFail();
            $credentialToUpdate->password = $fields['password_confirmation'];
            $credentialToUpdate->signed_route = null;
            $credentialToUpdate->update();
            return $this->dataResponse('success', 201, __('msg.password_change_successful'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, 'Email address does not exist');
        }
    }
    public function onForgotPassword(Request $request)
    {
        $fields = $request->validate([
            // 'type' => 'required|in:create,reset,lock,otp',
            // 'route' => 'required',
            'email' => 'required|email',
            // 'phone_number' => 'nullable'
        ]);
        try {
            $credentialModel = CredentialModel::where('email', $fields['email'])->firstOrFail();
            $userModel = $credentialModel->user;
            $firstName = $userModel->first_name;
            $lastName = $userModel->last_name;
            $temporaryUrl = $this->onCreateSignedUrl($credentialModel, 'create', '/password/create');
            $firstLastName = $firstName . ' ' . $lastName;
            $this->onSendSignedUrl($fields['email'], 'create', $firstLastName, $temporaryUrl);
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, 'Email does not exist');
        }
    }
}

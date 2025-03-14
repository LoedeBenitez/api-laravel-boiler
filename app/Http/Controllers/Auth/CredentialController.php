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

    // OMG API
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
            'employee_id' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
        ]);
        try {
            $credentialToUpdate = Credential::where('employee_id', $fields['employee_id'])->first();
            $isNotFirstLogin = $credentialToUpdate->is_first_login == 0;
            $signedRoute = explode($credentialToUpdate->signed_route, '|')[0];
            if (!$credentialToUpdate || ($isNotFirstLogin && $signedRoute == 'create')) {
                return $this->dataResponse('error', 400, __('msg.password_change_unsuccessful'));
            }
            $credentialToUpdate->password = bcrypt($request->password);
            $credentialToUpdate->is_first_login = 0;
            $credentialToUpdate->is_locked = 0;
            $credentialToUpdate->signed_route = null;
            $credentialToUpdate->update();
            return $this->dataResponse('success', 201, __('msg.password_change_successful'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onForgotPassword(Request $request)
    {
        $fields = $request->validate([
            'type' => 'required|in:create,reset,lock,otp',
            'route' => 'required',
            'email' => 'nullable|email',
            'phone_number' => 'nullable'
        ]);
        try {
            if (isset($fields['email'])) {
                $personalInformation = PersonalInformation::where('personal_email', $fields['email'])
                    ->orWhere('company_email', $fields['email'])
                    ->first();
                if (!$personalInformation) {
                    return $this->dataResponse('error', 404, __('msg.email_not_found'));
                }
                $credentialQuery = Credential::where('employee_id', $personalInformation->employee_id);
                if ($fields['type'] == 'create') {
                    $credentialQuery->where('is_first_login', 1);
                }
                $temporaryUrl = $this->onCreateSignedUrl($credentialQuery, $fields['type'], $fields['route']);
                $full_name = $personalInformation->first_name . ' ' . $personalInformation->last_name;
                $this->onSendSignedUrl($fields['email'], $fields['type'], $full_name, $temporaryUrl);
                return $this->dataResponse('success', 200, __('msg.email_sent'));
            } else {
                $contactNumber = ContactNumber::where('phone_number', $fields['phone_number'])->first();
                $credentialQuery = $contactNumber->personalInformation->credential;
                $this->onCreateSignedUrl($credentialQuery, $fields['type'], $fields['route']);
                return $this->dataResponse('success', 200, __('msg.signed_url_register'));
                if (!$contactNumber) {
                    return $this->dataResponse('error', 404, __('msg.phone_not_found'));
                }
            }
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ContactNumber;
use App\Models\Credential;
use App\Models\CredentialModel;
use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Exception;

use App\Traits\ResponseTrait;
use App\Traits\MailTrait;
use App\Traits\OtpTrait;

class SignedUrlController extends Controller
{
    use ResponseTrait;
    use MailTrait;
    use OtpTrait;

    public function onCheckToken(Request $request)
    {
        try {
            return $this->dataResponse('success', 200, __('msg.token_valid'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onCheckSignedURL($token)
    {
        try {
            $credentialSignedRoute = CredentialModel::where('signed_route', $token)->first();
            if (!$credentialSignedRoute) {
                return $this->dataResponse('error', 200, __('msg.token_invalid'));
            }
            return $this->dataResponse('success', 200, __('msg.token_valid'), $credentialSignedRoute);
        } catch (Exception $exception) {
            return $this->dataResponse('error', 404, $exception->getMessage());
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
    public function onSendOtp(Request $request)
    {
        $fields = $request->validate([
            'phone_number' => 'required',
        ]);
        try {
            $phoneNumber = $fields['phone_number'];
            $saveOtp = $this->onSaveOtp($phoneNumber);
            if (isset($saveOtp['success'])) {
                return $this->dataResponse('success', 200, __('msg.otp_sent'));
            }
            return $this->dataResponse('error', 400, __('msg.otp_failed'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onValidateOtp(Request $request)
    {
        $fields = $request->validate([
            'otp' => 'required',
            'phone_number' => 'required',
        ]);
        try {
            $otp = $fields['otp'];
            $phoneNumber = $fields['phone_number'];
            $validatedOtp = $this->onValidateOtpRequest($otp, $phoneNumber);
            if (isset($validatedOtp['success'])) {
                return $this->dataResponse('success', 200, __('msg.otp_valid'));
            }
            return $this->dataResponse('error', 400, __('msg.otp_invalid'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
}

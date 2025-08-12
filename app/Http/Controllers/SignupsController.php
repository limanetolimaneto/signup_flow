<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SignupsController extends Controller
{
    /**
     * Handle initial sign-up form submission:
     * - Validate email and password.
     * - Generate confirmation code.
     * - Store code and timestamp in session.
     * - Send code by email.
     * - Show confirmation code form.
     */
    public function confirm(Request $request)
    {
        $codeKey = null;
        $errorKey = null;  
        $errorMessage = [];
        $email = $request->username;
        // Validation rules for email and password
        $validator = Validator::make($request->all(), [
            'username' => 'required|email',
            'password' => [
                'required',
                'string',
                Password::min(6)->letters()->numbers(),
                'confirmed',
            ],
        ]);
        
        if ($validator->fails()) {
            $errorKey = 'form_1';

            if ($validator->errors()->has('username')) {
                $errorMessage[] = 'The email address you entered is not valid. Please check and try again.';
            }
            if ($validator->errors()->has('password')) {
                $errorMessage[] = 'The password you entered is not valid or does not meet the requirements.';
            }

            // Return to sign-up form with errors
            return $this->returnCodeError($email, $errorMessage, $codeKey, $errorKey );
        }
        
        // Generate 4-digit code with leading zeros
        $stringCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $email = $request->username;
        $emailKey = str_replace(['@', '.'], '_', $email);

        // Store code and timestamp in session
        Session::put([
            "code_{$emailKey}" => $stringCode,
            "code_{$emailKey}_created_at" => time(),
        ]);
        session()->save();

        // Send confirmation code email
        $content = "Your number code is : " . $stringCode;
        Mail::to($email)->send(new CodeEmail($content));

        // Show confirmation form
        $codeKey = 'confirm';
        return view("welcome", ['codeKey' => $codeKey, 'email' => $email]);
    }

    /**
     * Handle confirmation code form submission:
     * - Check if the code exists and is within valid time frame.
     * - Compare user input with stored code.
     * - Show success or error accordingly.
     */
    public function confirmCode(Request $request)
    {
        $email = $request->username;
        $emailKey = str_replace(['@', '.'], '_', $email);

        // Validate input code digits presence
        $digits = [$request->number_1, $request->number_2, $request->number_3, $request->number_4];
        foreach ($digits as $digit) {
            if (!ctype_digit($digit) || strlen($digit) !== 1) {
                $errorMessage = ["Each code field must be exactly 1 digit."];
                return $this->returnCodeError($email, $errorMessage, 'confirm', 'form_2');
            }
        }
        $codeInput = implode('', $digits);
       
        // Check if code and timestamp exist in session
        if (session()->has("code_{$emailKey}") && session()->has("code_{$emailKey}_created_at")) {
            $createdAt = session("code_{$emailKey}_created_at");
            $now = time();
            $validDuration = 5 * 60; // Code valid for 5 minutes

            if (($now - $createdAt) <= $validDuration) {
                $storedCode = session("code_{$emailKey}");

                if ($storedCode === $codeInput) {
                    // Clear code from session after success
                    session()->forget("code_{$emailKey}");
                    session()->forget("code_{$emailKey}_created_at");

                    // Show success final view
                    return view("welcome", ['codeKey' => 'final', 'email' => $email]);
                } else {
                    $errorMessage [] = "Invalid code. Please check your email and try again.";
                    $codeKey = 'confirm';
                    $errorKey = 'form_2';
                    return $this->returnCodeError($email, $errorMessage,$codeKey,$errorKey );
                }
            } else {
                // Expired code: remove from session and ask user to restart
                session()->forget("code_{$emailKey}");
                session()->forget("code_{$emailKey}_created_at");

                return view("welcome", [
                    'codeKey' => null,
                    'errorKey' => 'form_1',
                    'errorMessage' => ["Your verification session has timed out. Please start the registration process again."],
                    'email' => $email,
                ]);
            }
        } else {
            // No code found in session - invalid flow
            return view("welcome", [
                'codeKey' => null,
                'errorKey' => 'form_1',
                'errorMessage' => ["No verification code found. Please start the registration process again."],
                'email' => $email,
            ]);
        }
    }

    /* 
     * Handle code correction:
     *  -  It hides the error screen according to the variable $codeKey 
    */
    public function confirmCodeError(Request $request)
    {
        return view('welcome',[
            'codeKey' => 'confirm',
            'email' => $request->username,
        ]);
    }

    /**
     * Helper function to return code confirmation error with form_2 errorKey.
     */
    private function returnCodeError($email, $errorMessage, $codeKey, $errorKey)
    {
        return view("welcome", [
            'codeKey' => $codeKey,
            'errorKey' => $errorKey,
            'errorMessage' => $errorMessage,
            'email' => $email,
        ]);
    }
}

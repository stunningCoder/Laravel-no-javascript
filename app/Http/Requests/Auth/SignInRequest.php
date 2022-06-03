<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\RequestException;
use App\Marketplace\PGP;
use App\Rules\Captcha;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignInRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
	        // 'captcha' => ['required', new Captcha()],
            'loginuser' => 'required|exists:users,loginuser',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'loginuser.required' => 'Hey, Username is required',
            'loginuser.exists' => 'Oops... Your ManCave login details are incorrect!',
            'password.required' => 'Hey, password is required',
            // 'captcha.required' => 'Hey, pay attention! Captcha is required',

        ];
    }


    public function persist() {

        $user = User::where('loginuser', $this->loginuser)->first();
        if ($user == null) {
            throw new RequestException('Hey, your ManCave login details are incorrect!');
        }
        // check if the password match
        if (!Hash::check($this->password, $user->password)) {
            throw new RequestException('Hey, your ManCave login details are incorrect!');
        }

        auth()->login($user);
        auth()->logoutOtherDevices($user->password);
        $user->last_seen = Carbon::now();
        // $user->is_online = true;
        $user->save();
        session()->regenerate();
        // user does not have 2fa enabled, log him in straight away
        if ($user->login_2fa == false) {
            return redirect()->route('profile.index');
        }

        $validationString = str_random(10);
        $messageToEncrypt = "To verify login please copy validation string to the matching field.\nValidation string:" . $validationString;
        $encryptedMessage = PGP::EncryptMessage($messageToEncrypt, $user -> pgp_key);

        // save to sessions
        session() -> put('login_validation_string', bcrypt($validationString));
        session() -> put('login_encrypted_message', $encryptedMessage);

        // return route to verify 2fa
        return redirect() -> route('auth.verify');
    }
}


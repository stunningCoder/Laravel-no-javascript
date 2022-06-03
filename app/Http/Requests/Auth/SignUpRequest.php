<?php

namespace App\Http\Requests\Auth;

use App\Marketplace\Encryption\Cipher;
use App\Marketplace\Encryption\Keypair;
use App\Marketplace\Utility\Mnemonic;
use App\Rules\Captcha;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Defuse\Crypto\Crypto;

class SignUpRequest extends FormRequest {

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
            'loginuser' => 'required|different:username|unique:users|alpha_num|min:4|max:12',
            'username' => 'required|different:loginuser||unique:users|alpha_num|min:4|max:12',
            'password' => 'required|confirmed|min:8',

        ];
    }

    /**
     * Get messages for validation rules
     *
     * @return array
     */
    public function messages() {
        return [
            // 'captcha.required' => 'Hey, are you nuts? The captcha is required!',
            'loginuser.required' => 'Hey, are you crazy? the Username is required!',
            'loginuser.different' => 'Hey, are you stoned? Usernames must be different than your Display Name!',
            'username.different' => 'Hey, what are you smoking? Display Names must be different than Usernames!',
            'username.required' => 'Hey, pay attention. Display Names are required pal!',
            'username.min' => 'Hey, are you in a rush? Usernames must have at least 4 or more characters!',
            'username.unique' => 'Hey, this username is restricted, no big deal, just pick another one!',
            'username.max' => 'Hey, paranoid? Usernames cannot be longer than 12 characters!',
            'username.alpha_num' => 'Hey, you can only use alpha-numeric characters with no spaces for username',
            'password.required' => 'Hey, you didnt think this would work did you? Password is required!',
            'password.min' => 'Hey, happy go luck, passwords must have at least 8 or more characters!',
            'password.confirmed' => 'Hey, you are not paying attention, password must be confirmed!',
            'password.different' => 'Hey, what about security? Password can\'t be same as your username!',
        ];
    }

    /**
     * Try to generate keys for user and complete registration
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \ParagonIE\EasyRSA\Exception\InvalidKeyException
     */
    public function persist() {

        //check if there is referral id
        if ($this->refid !== null) {
            $referred_by = User::where('referral_code', $this->refid)->first();
        }
        else
            $referred_by = null;


        // create users public and private RSA Keys
        $keyPair = new Keypair();
        $privateKey = $keyPair->getPrivateKey();
        $publicKey =   $keyPair->getPublicKey();
        // encrypt private key with user's password
        $encryptedPrivateKey = Crypto::encryptWithPassword($privateKey, $this->password);

        $mnemonic = (new Mnemonic())->generate(config('marketplace.mnemonic_length'));

        $user = new User();
        $user->loginuser = $this->loginuser;
        $user->username = $this->username;
        $user->password = bcrypt($this->password);
        $user->mnemonic = bcrypt(hash('sha256', $mnemonic));
        $user->referral_code = strtoupper(str_random(6));
        $user->msg_public_key = encrypt($publicKey);
        $user->msg_private_key = $encryptedPrivateKey;
        $user -> referred_by = optional($referred_by) -> id;
        $user->save();

        // generate vendor addresses
        $user->generateDepositAddresses();

        session()->flash('mnemonic_key', $mnemonic);
    }
}


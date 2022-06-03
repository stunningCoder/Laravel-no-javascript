<?php

namespace Modules\MultiCurrency\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MultiCurrency\Converter\Converter;
use Modules\MultiCurrency\Converter\Currencies;

class ChangeLocalCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency' => 'required'
        ];
    }
    public function messages(){
        return [
            'currency.required' => 'Currency is required'
        ];
    }
    public function persist(){
        if (!in_array($this->currency, Currencies::getCurrencies())){
            dd(1);
            throw new \Exception('Invalid currency');
        }
        $user = auth()->user();
        $user->local_currency = $this->currency;
        $user->save();

        session()->put('local_currency',$user->local_currency);

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }
}

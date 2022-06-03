<?php

namespace Modules\MultiCurrency\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\MultiCurrency\Http\Requests\ChangeLocalCurrencyRequest;

class MultiCurrencyController extends Controller
{
    public function change(ChangeLocalCurrencyRequest $request){

        try{
            $request->persist();
        } catch(\Exception $e){

        }
        return redirect()->back();
    }
}

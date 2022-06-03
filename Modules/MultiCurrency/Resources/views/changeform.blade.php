<h3 class="mt-4">Local Currency:</h3>
<hr>
<div class="row">

    <div class="col-md-12">
        <form action="{{route('multicurrency.change')}}" method="post">
            {{csrf_field()}}

            <div class="form-group">
                <select name="currency" id="" class="form-control">
                    @foreach(\App\Marketplace\Utility\CurrencyConverter::getSupportedCurrencies() as $currency)
                        <option value="{{$currency}}" @if(auth()->user()->getLocalCurrency() == $currency) selected @endif>{{$currency}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-outline-secondary">Change currency</button>
            </div>
        </form>
        <p class="text-muted">Your current local currency is: {{auth()->user()->getLocalCurrency()}}</p>
    </div>
</div>

<?php /** @var \App\Purchase $purchase */ ?>
<div class="col-md-6">

    @if($purchase -> shipping)
        <h3 class="mb-2">Delivery</h3>
        <table class="table">
            <tr>
                <td>Shipping name:</td>
                <td>{{ $purchase -> shipping -> name }}</td>
            </tr>
            <tr>
                <td>Delivery time:</td>
                <td>{{ $purchase -> shipping -> duration }}</td>
            </tr>
            <tr>
                <td>Shipping price:</td>
                <td><strong>@include('includes.currency', ['usdValue' => $purchase -> shipping -> price])</strong></td>
            </tr>
        </table>
    @else
        @if($purchase->offer->product->isAutodelivery())
            <h3 class="mb-2">Automatic delivery:</h3>

            {{-- If the buyer deposited enough sum --}}
            @if($purchase -> isBuyer() && $purchase -> enoughBalance())
                <textarea class="form-control disabled" readonly rows="10">{{ $purchase -> delivered_product }}</textarea>
            @elseif($purchase -> isBuyer())
                <div class="alert alert-warning">
                    You must pay to address and the system will deliver you content here.
                </div>
            @elseif($purchase -> isVendor())
                <div class="alert alert-warning">
                    {!!  nl2br(e($purchase->delivered_product)) !!}
                </div>
            @endif

        @else
            <h3 class="mb-2">Manual delivery:</h3>
        @endif

    @endif
</div>

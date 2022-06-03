@extends('master.main')

@section('title','Product Clone')

@section('content')

    <div class="modal fade in show position-static d-block" tabindex="-1" role="dialog">
        <form action="{{route('profile.vendor.product.clone.post',$product)}}" method="post">
            {{csrf_field()}}
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color: #1414147a;">
                    <div class="modal-header">
                        <h5 class="modal-title">Product cloning - {{$product->name}}</h5>

                    </div>
                    <div class="modal-body">
                        <p>
                            Cloning product will also duplicate all offers and images as inactive.<br/>
                            Click edit to review before publishing.<br/>
                            Please confirm product cloning.<br/>
                        </p>

                        <h4>Important Notice:</h4>

                        <p>If you are here to publish your “old ad” that you manually published but then deactivated for whatever reasons, you can continue with the activation, for everyone else please take 30 seconds to read the following, it will save you a lot of hassle down the line.</p>
                        <p>Placing “duplicate ads” with the same or “altered/spun headlines” is a violation of our terms of service, this is in place to give every vendor a fair chance towards winning sales and making $.</p>
                        <p>We introduced a “Clone” feature with the intention to give all vendors the opportunity to generate “quick ads” for one product that they may have for sale and “edit” their cloned ad(s) with each “unique product” before its activation.</p>
                        <p>As with any feature that offer “convenience” there is a risk of abuse, in this particular case, a malicious vendor may misuse our “clone” feature in order to get an unfair advantage over other vendors by generating “cloned listings” of the “same product” using spun titles & descriptions. </p>
                        <p>As per our terms of use, the account that is found to be in violation of our terms will be limited followed by issuing a fixed penalty of $250 for their first violation and $750 for the subsequent up until the third, after which we will begin parting ways in a manner that may be fairly disruptive to such vendors business with no possibility of any appeals or return to our market, any remaining funds in the escrow wallet can be withdrawn within the 90 days after the third limitation was placed, after 90 days the account will be restricted from login, period.</p>
                        <p>We would like to acknowledge the fact that we support our vendors towards attracting and connecting with buyers using best practices that are fair for everyone so that we can continue to provide the highest quality of service that everyone expects and deserves from our marketplace.</p>

                        <label>
                            <input type="checkbox" name="iagreeterms"> I have read and understood the <a>Terms of Use</a>.
                        </label>


                    </div>
                    <div class="modal-footer text-center justify-content-center">
                        <button type="submit" class="btn btn-success">Confirm</button>
                        <a href="{{route('profile.vendor')}}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>

        </form>
    </div>

@stop

@extends('master.profile')

@section('profile-content')
    @include('includes.flash.error')
    @include('includes.flash.success')
    @include('includes.validation')

    <h1 class="my-3">Unlock Messages</h1>
    <hr>
    <div class="row justify-content-center">
       <div class="col-md-12">
           <p>Enter your password so we can secure your message to the buyer.</p>
       </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{route('profile.sales.messages.decrypt.post.sale')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <input type="password" name="password" class="form-control" >
                </div>
                <div class="form-group text-center">
                    <a href="{{ session()->get('return_cofirm_sale_url') }}" class="btn btn-secondary" style="margin-left: auto;">Dismiss</a>
                    <button class="btn  btn-outline-success d-inline" type="submit" style="width: auto">Unlock</button>
                    <a href="{{ session()->get('return_cofirm_sale_url') }}" class="btn btn-secondary" style="margin-left: auto;">Return to Confirm sale</a>
                </div>
            </form>
        </div>


    </div>



@stop

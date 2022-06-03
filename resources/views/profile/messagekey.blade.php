@extends('master.profile')

@section('profile-content')
    @include('includes.flash.error')
    @include('includes.flash.success')
    @include('includes.validation')


    <h1 class="my-3">Messages.</h1>
    <hr>
    <div class="row justify-content-center">
       <div class="col-md-12">
           <p>At ManCave Market, your inbox is securely hashed, to view your messages please enter your ManCave password.</p>
       </div>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{route('profile.messages.decrypt.post')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <input type="password" name="password" class="form-control" >
                </div>
                <div class="form-group text-center">
                    <button class="btn  btn-outline-success" type="submit">Decrypt My Messages</button>
                </div>
            </form>
        </div>


    </div>



@stop

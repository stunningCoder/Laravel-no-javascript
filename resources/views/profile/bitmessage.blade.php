@extends('master.profile')

@section('profile-content')


    <h1 class="my-3">Bitmessage.</h1>
    <hr>
    <p class="my-3">- At ManCave Market you can forward all your Notifications directly to your BitMessage.</p>
    <p class="my-3">- This way you will stay upto date with ManCave securely even when you are not logged in.</p>
    <p class="my-3">- Feel Free to <a href="https://github.com/Bitmessage/PyBitmessage" target="_blank"><b>Download BitMessage</b></a> On GitHub.</p>
    <p>BitMessage Service Status is currently: @if($enabled) <span class="badge badge-success">Enabled</span> @else <span class="badge badge-danger">Disabled</span> @endif</p>

    <br>

    @if($user->bitmessage_address !== null)
    <div class="alert @if($enabled) alert-info @else alert-warning @endif">You have configured your Bitmessage address,@if($enabled) notifications will be forwareded @else however service is not currently enabled @endif </div>
    @else
        <div class="alert alert-warning">In order to forward ManCave notifications you must first enter your Bitmessage address!</div>
    @endif


    @if($user->bitmessage_address == null)
        <h4>Your BitMessage Address:</h4>
    @else
        <h4>Change Bitmessage address</h4>
        <p class="text-muted">Current address: {{$user->bitmessage_address}}</p>
    @endif
    <hr>
    @include('includes.flash.error')
    @include('includes.flash.success')
    @include('includes.flash.invalid')
    <form action="{{route('profile.bitmessage.sendcode')}}" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="address">Paste Your BitMessage Address below and Press "Send Confirmation Message"</label>
            <input type="text" name="address" id="" class="form-control" id="address" value="@if(session()->has('bitmessage_confirmation')) {{session()->get('bitmessage_confirmation')['address']}} @endif">
        </div>
        <div class="form-group">
            @if(session()->has('bitmessage_confirmation'))
                <button type="submit" class="btn btn-outline-secondary">Resend Confirmation Message</button>
                <p class="text-muted">You can request new confirmation message every {{config('bitmessage.confirmation_msg_frequency')}} {{str_plural('second',config('bitmessage.confirmation_msg_frequency'))}}</p>
            @else
                <button type="submit" class="btn btn-outline-primary">Send Confirmation Message</button>
            @endif
        </div>
    </form>

    @if(session()->has('bitmessage_confirmation'))
        <div class="row">
            <div class="col-md-6">
                <form action="{{route('profile.bitmessage.confirmcode')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="code">Confirmation code</label>
                        <input type="text" name="code" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary">Confirm address</button>
                    </div>
                </form>
            </div>
        </div>
    @endif


@stop

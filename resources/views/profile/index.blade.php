@extends('master.profile')

@section('profile-content')
    @include('includes.flash.error')
    @include('includes.flash.success')
    
    <div class="bg-white" style="width: 340px; height: 340px; padding: 20px;">
        {{ \QrCode::size(300)->generate('abc is abc') }}
    </div>
    <h1 class="my-3">Settings.</h1>

    <br>

    <div class="form-row my-2">
        <label for="loginusername" class="col-form-label col-md-2">Private Username:</label>
        <div class="col-md-10">
            <input type="text" disabled class="form-control" value="{{ $user->loginuser  }}">
        </div>
    </div>
    <div class="form-row my-2">
        <label for="old_password" class="col-form-label col-md-2">MC Public Name:</label>
        <div class="col-md-10">
            <input type="text" disabled class="form-control" value="{{ $user->username  }}">
        </div>
    </div>

    <h3 class="mt-4">Change ManCave Password:</h3>
    <hr>
    <form action="{{ route('profile.password.change') }}" method="POST" class="justify-content-between">
        {{ csrf_field() }}
        <div class="form-row my-2">
            <label for="old_password" class="col-form-label col-md-2">Current password:</label>
            <div class="col-md-10">
                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Type your current password">
            </div>
        </div>
        <div class="form-row my-2">
            <label for="new_password" class="col-form-label col-md-2">New password:</label>
            <div class="col-md-5">
                <input type="password" class="form-control @error('new_password', $errors) is-invalid @enderror" id="new_password" name="new_password" placeholder="Type new password">
            </div>
            <div class="col-md-5">
                <input type="password" class="form-control @error('new_password', $errors) is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
            </div>
        </div>
        <div class="form-row text-right justify-content-between">
            <div class="col-md-9 text-left">
                @error('new_password', $errors)
                    <p class="invalid-feedback d-block">{{ $errors -> first('new_password') }}</p>
                @enderror
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="submit">Change password</button>
            </div>
        </div>
    </form>
    <h3 class="mt-4">Your Telegram Username:</h3>
<hr>
<form action="{{ route('profile.telegram.change') }}" method="POST" class="justify-content-between">
    {{ csrf_field() }}

    <div class="form-row my-2">
        <label for="change_telegram" class="col-form-label col-md-3">My Telegram Username is:</label>
        <div class="col-md-5">
            <input type="text" class="form-control" value="@if($user->telegram_username != 'null'){{ $user->telegram_username}}@endif " id="telegram_username" name="telegram_username" placeholder="Type your Telegram Username">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </div>
</form>
    @if(config('marketplace.multicurreny') == true)
        @if(\App\Marketplace\Utility\CurrencyConverter::isEnabled())
            @include('multicurrency::changeform')
        @endif
    @endif
    <h3 class="mt-4">Two-Factor Authentication:</h3>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <label>Your ManCave Account 2FA Log-in Status:</label>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group" role="group">
                <a href="{{ route('profile.2fa.change', true) }}" class="btn @if(auth() -> user() -> login_2fa == true) btn_success @else btn-outline-grey @endif">On</a>
                <a href="{{ route('profile.2fa.change', 0) }}" class="btn @if(auth() -> user() -> login_2fa == false) btn_red @else btn-outline-grey @endif">Off</a>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Your ManCave Referral Link:</h3>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <input type="url" readonly class="form-control disabled" value="{{ route('auth.signup', auth() -> user() -> referral_code) }}">
            <p class="text-muted">You will earn $ in commission each time your referral(s) will make a purchase on ManCave Market.</p>
        </div>
    </div>

    <h3 class="mt-4">Add/Remove/Change Your Payment Addresses:</h3>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('profile.vendor.address') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-lg d-flex" name="address" id="address" placeholder="Paste your coin's &#8220;Public Key&#8220; here!">
                    </div>
                    <div class="col-md-2">
                        <select name="coin" id="coin" class="form-control form-control-lg d-flex">
                            <option> - Crypto - </option>
                            <?php
                                $coinConfirm = array();
                                foreach(auth() -> user() -> addresses as $address)
                                {
                                    $coninConfirm[$address->coin] = $address->address;
                                }
                            ?>
                            @foreach(config('coins.coin_list') as $supportedCoin => $instance)
                                <option value="{{ $supportedCoin }}"
                                    {{ isset($coinConfirm[$supportedCoin]) ? 'diabled' : '' }}
                                >{{ strtoupper(\App\Address::label($supportedCoin)) }}{{$instance}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-block btn-primary btn-lg">Change</button>
                    </div>
                </div>
            </form>
            <p class="text-muted"><b>NOTE:</b> You will receive $ for your refunds (as a buyer) or sales (as a vendor) on crypto address or a pub key that you will add here, add only one address/pub-key per coin.</p>


            @if(auth() -> user() -> addresses -> isNotEmpty())
                <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Address</th>
                        <th>Coin</th>
                        <th class="text-right">Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(auth() -> user() -> addresses as $address)
                        <tr>
                            <td>
                                <input type="text" readonly class="form-control" value="{{ $address -> address }}">
                            </td>
                            <td><span class="badge badge-info">{{ strtoupper($address -> coin) }}</span></td>
                            <td class="text-muted text-right">
                                {{ $address -> added_ago }}
                            </td>
                            <td class="text-right"><a href="{{ route('profile.vendor.address.remove', $address) }}" class="btn btn-primary"><i class="fa fa-trash mr-1"></i>Remove</a></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            @else
                <div class="alert alert-danger">Your crypto address/pub-key list is empty, please fill in your BTC and XMR address/pub-keys!</div>
            @endif
        </div>
    </div>

@stop


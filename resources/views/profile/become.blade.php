@extends('master.profile')

@section('profile-content')
    @include('includes.flash.error')
    @include('includes.flash.success')

    <h1 class="my-3">Wanna join ManCave as a vendor?</h1>

    <br>

    <p class="my-3">- Our current vendor fee is <strong>{{ $vendorFee }} USD</strong>.</p>

    <p class="my-3">- Becoming a vendor on ManCave is not for everyone, we strive for the highest customer service standards.</p>

    <p class="my-3">- If your dream is to get rich quick without providing our customers a real value for their $ then look elsewhere.</p>

    <p class="my-3">- Vendors who do not adhere to our "Vendor Terms" and "High Customers Service Standards" have a shorter lifespan on ManCave Market.</p>

    <p class="my-3">- We believe in providing a real value, if you share similar beliefs and you have what it takes to serve our worldwide audience then we welcome you as a vendor to the ManCave!</p>
     
    <br>
     
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Coin</th>
                <th>Address</th>
                <th>Vendor Fee</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($depositAddresses as $depositAddress)
            <tr>
                <td>
                    <span class="badge badge-info">{{ strtoupper($depositAddress -> coin) }}</span>
                </td>
                <td>
                    <input type="text" readonly class="form-control" value="{{ $depositAddress -> address }}"/>
                </td>
                <td class="text-right">
                    <span class="badge badge-primary">{{ $depositAddress -> target }}</span>
                </td>
                <td class="text-right">
                    @if($depositAddress -> isEnough())
                        <span class="badge badge-success">Enough funds</span>
                    @endif
                    <span class="badge badge-info">{{ $depositAddress -> balance }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <form action="{{ route('profile.vendor.become') }}" class="form-inline">
        <button type="submit" class="btn btn-lg btn-success">
            <i class="fas fa-file-signature mr-2"></i>
            Become a Vendor
        </button>
    </form>


@stop

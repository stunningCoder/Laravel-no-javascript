@extends('master.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .levelusername{
        color:black;
    }
</style>
@section('title','Product - ' . $product -> name )
@section('content')


    <nav class="main-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Products</a>
            </li>
            @foreach($product -> category -> parents() as $ancestor)
                <li class="breadcrumb-item" aria-current="page"><a
                            href="{{ route('category.show', $ancestor) }}">{{ $ancestor -> name }}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('category.show', $product -> category) }}">{{ $product -> category -> name }}</a>
            </li>
        </ol>
    </nav>

    <style>
        @@media (min-width: 992px) {

            #pslider{
                width: 350px;
            }

            .slides{
                height: 340px;
                overflow: hidden;
            }

            .slide{
                width: 340px !important;
                height: 340px !important;
                overflow: hidden;
            }
        }

        .slide:hover img{
            -o-object-position:center;
            object-position:center;
            -o-object-fit:contain;
            object-fit:contain;
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 340px;
        }

        .slide img{
            -o-object-position:center;
            object-position: center;
            -o-object-fit:cover;
            object-fit:cover;
            width: auto;
        }
        .slider > a.sl_btn:active{
            color: #e60000;
        }
        .sl_btn{
            border: 1px solid;
        }

        .coin_display .fab {
            font-size: 25px;
            padding: 2px;
        }

        .coin_display .fas.fa-coins {
            font-size: 25px;
            padding: 2px;
        }
    </style>
    <div class="row">
        <div class="col-md-5">
            <div class="slider" id="pslider">
                <div class="slides">
                    @php $i = 1; @endphp
                    @foreach($product -> images() -> orderBy('first', 'desc') -> get() as $image)
                        @php
                            $img = asset('storage/' . $image -> image);
                           // $thumb = asset('storage/products/thumbnail/' . str_replace('products/', '', $image -> image));
                        @endphp
                        <div class="slide" id="slide-{{ $i++ }}">
                            <a target="_blank" href="{{ $img }}" title="Click to view the original image">
                                <img src="{{ $img }}">
                            </a>
                        </div>
                    @endforeach
                </div>

                @php $i = 1; @endphp
                @foreach($product -> images as $image)
                    <a class="sl_btn" href="#slide-{{ $i }}">{{ $i++ }}</a>
                @endforeach
            </div>
            <div class="card mb-2" id="pslider">
                <div class="card-body">
                    <h6>
                        <i class="fas fa-shield-alt"></i>
                        ManCave Guarantee!
                    </h6>
                    <div class="text-muted">
                        You are Escrow protected on all purchases this excludes "Finalize Early" items!
                    </div>
                    <br>
                    <a href="{{route('profile.tickets.reportitem',['user'=>$product->user->username ,'itemId' => $product->id])}}"  class="btn btn-outline-info"><span class="fa fa-warning"></span> Report this item!</a>
                </div>
            </div>
            <div class="card" id="pslider">
                <div class="card-header">
                    Seller Information
                    <span class="btn-group">
                        <a class="btn btn-light btn-sm" href="{{ route('vendor.show', $product -> user) }}">
                            <span class="d-inline levelusername">@if(Cache::has('is_online' . $product -> user -> id)) <span class="useronline">●</span> @else <span class="useroffline">●</span> @endif {{ $product -> user -> username }}</span>

                            <span class="d-inline btn btn-primary active btn-sm">Level {{$product->user->vendor->getLevel()}}</span>
                        </a>
                    </span>
                </div>
                <div class="card-body">

                    @php
                        $vendor = $product->user;
                    @endphp
                    <div class="row my-1 text-md-center">
                        <div class="col-4">
                            <span class="fas fa-plus-circle text-success"></span> {{$vendor->vendor->countFeedbackByType('positive')}}
                        </div>
                        <div class="col-4">
                            <span class="fas fa-stop-circle text-secondary"></span> {{$vendor->vendor->countFeedbackByType('neutral')}}

                        </div>
                        <div class="col-4">
                            <span class="fas fa-minus-circle text-danger"></span> {{$vendor->vendor->countFeedbackByType('negative')}}
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('profile.messages').'?otherParty='.$product -> user ->username}}" class="btn btn-outline-secondary" style="border-color: #4caf50;color: #fff"><span class="fas fa-envelope"></span> Send message</a>
                    <a href="{{route('search',['user'=>$product->user->username])}}"  class="btn btn-outline-info">Seller's products ({{$product -> user ->products()->count()}})</a>

                </div>
            </div>
        </div>

        <div class="col-md-7">
            @include('includes.flash.error')

            <h2>{{ $product -> name }}</h2>
            <hr>

            <div class="row">
                <div class="col-md-12 text-left">

                    <form action="{{ route('profile.cart.add', $product) }}"  method="POST">
                        {{ csrf_field() }}

                        <table class="table border-0 text-left table-borderless">
                            <tbody>

                            <tr>
                                <td class="text-right text-muted">Feedback:</td>
                                <td><a href="{{route('vendor.show.feedback',['user'=>$product->user->vendor])}}">
                                        @include('includes.purchases.stars', ['stars' => (int)$product->avgRate('quality_rate')])</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right text-muted">Item Type</td>
                                <td>
                                    <strong class="badge badge-info">{{ ucfirst($product -> type) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right text-muted">Offer(s)</td>
                                <td>
                                    <ul>
                                        @foreach($product -> offers as $offer)
                                            <li>

                                                <strong>@include('includes.currency', ['usdValue' => $offer -> dollars])</strong> per {{ str_plural($product -> mesure, 1) }},
                                                for at least {{ $offer -> min_quantity }} {{ str_plural('product', $offer -> min_quantity) }}
                                            </li>
                                        @endforeach
                                    </ul>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right text-muted">Accepts</td>
                                <td class="coin_display">
                                    @foreach($product -> getCoins() as $coin)
                                        @if($coin == 'btc')
                                            <i class="fab fa-bitcoin"></i>
                                            {{-- <img src="{{asset('/img/bitcoin.svg')}}" style="height: 32px;width:32px" /> --}}
                                        @endif
                                        @if($coin == 'xmr')
                                            <i class="fab fa-monero"></i>
                                        @endif
                                        @if($coin == 'stb')
                                            <i class="fas fa-coins"></i>
                                        @endif
                                        {{-- <span class="badge badge-indigo">{{ strtoupper(\App\Purchase::coinDisplayName($coin)) }}</span> --}}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right text-muted">Available/Sold</td>
                                <td>
                                    <span class="badge badge-light">{{ $product -> quantity }} {{ str_plural($product -> mesure, $product -> quantity) }}</span> -
                                    <span class="badge badge-light">{{ $product -> orders }} {{ str_plural($product -> mesure, $product -> orders) }} </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @if($product->user->vendor->experience < 0)
                                        <p class="text-danger border border-danger rounded p-1 mt-2"><span
                                                    class="fas fa-exclamation-circle"></span> Trade with caution!
                                        </p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                @if($product -> isPhysical())
                                    <td class="text-muted text-right">
                                        <label for="delivery">Delivery:</label>
                                    </td>
                                    <td>
                                        <select name="delivery" id="delivery"
                                                class="form-control form-control-sm @if($errors -> has('delivery')) is-invalid @endif">
                                            @foreach($product -> specificProduct() -> shippings as $shipping)
                                                <option value="{{ $shipping -> id }}">{{ $shipping -> long_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                            </tr>


                            <tr class="bg-light">

                                <td class="text-right text-muted">
                                    <label for="coin">Pay With:</label>
                                </td>
                                <td>
                                    @if(count($product -> getCoins()) > 1)

                                        @php
                                            $userCoins = array_column(\App\Address::where('user_id', auth()->id())->get()->toArray(), 'coin');
                                            $isSelectedCoin = false;

                                            if (!empty($userCoins))
                                            {
                                                echo '<p>You have coins: <strong>' . implode(',', $userCoins) . '</strong></p>';
                                            }

                                        @endphp

                                        <select name="coin" id="coin" class="form-control form-control-sm">
                                            @foreach($product -> getCoins() as $coin)
                                                <option @if (!$isSelectedCoin && in_array($coin, $userCoins))
                                                            selected
                                                        @php
                                                            $isSelectedCoin = true;
                                                        @endphp
                                                        @endif

                                                        value="{{ $coin }}">{{ strtoupper(\App\Purchase::coinDisplayName($coin)) }}</option>
                                            @endforeach
                                        </select>
                                    @elseif(count($product -> getCoins()) == 1)
                                        <span class="badge badge-mblue">{{ strtoupper(\App\Purchase::coinDisplayName($product -> getCoins()[0])) }}</span>
                                        <input type="hidden" name="coin" value="{{ $product -> getCoins()[0] }}">
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td class="text-right text-muted">
                                    <label for="type">Sale Type:</label>
                                </td>
                                <td>
                                    @if(count($product -> getTypes()) > 1)
                                        <select name="type" id="type" class="form-control form-control-sm">
                                            @foreach($product -> getTypes() as $type)
                                                <option value="{{ $type }}">{{ \App\Purchase::$types[$type] }}</option>
                                            @endforeach
                                        </select>
                                    @elseif(count($product -> getTypes()) == 1)
                                        <span class="badge badge-mblue">{{ \App\Purchase::$types[$product -> getTypes()[0]] }}</span>
                                        <input type="hidden" name="type" value="{{ $product -> getTypes()[0] }}">
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-light">

                                <td class="text-right text-muted">
                                    <label for="amount">Quantity:</label>
                                </td>
                                <td class="row">

                                    @if (auth() -> user() && !auth() -> user() -> addresses -> isNotEmpty())
                                        <div class="col-md-12" style="color: white; background-color: red; margin: .5rem; padding: .5rem; border-radius: .5rem">
                                            <h5>You must first add your refund address! <a style="color: #fff" href="{{ route('profile.index') }}#address">Click here</a></h5>
                                        </div>
                                    @endif

                                    <div class="col-md-5">
                                        <input type="number" min="1" name="amount" id="amount"
                                               value="1"
                                               max="{{ $product -> quantity }}"
                                               class="@if($errors -> has('amount')) is-invalid @endif form-control form-control-sm"
                                               placeholder="Amount of {{ str_plural($product -> mesure) }}"/>
                                    </div>
                                    <div class="col-md-7">
                                        @if($product->active == true)
                                            <button class="btn btn-sm btn-block mb-2 btn-primary"><i class="fas fa-plus mr-2"></i>{{ $product->quantity > 0 ? 'Add to cart' :  'Out of stock'}}
                                            </button>

                                        @else
                                            <p>Sorry, this product is not available!</p>
                                        @endif

                                        @auth

                                            @if(auth() -> user() -> isWhishing($product))
                                                <a href="{{ route('profile.wishlist.add', $product) }}"
                                                   class="btn btn-outline-secondary btn-block btn-sm"><i class="far fa-heart"></i> Remove
                                                    from
                                                    wishlist</a>
                                            @else
                                                <a href="{{ route('profile.wishlist.add', $product) }}"
                                                   class="btn btn-sm btn-block btn-outline-danger"><i
                                                            class="fas fa-heart"></i> Add to Wishlist</a>
                                            @endif

                                        @endauth

                                    </div>
                                </td>
                            </tr>


                            </tbody>
                        </table>

                    </form>
                    @include('includes.flash.invalid')

                </div>


            </div>
        </div>

        {{-- Shop with confidence --}}
        <div class="col-md-3">

        </div>

    </div>

    {{-- Product menu --}}
    <ul id="productsmenu" class="my-4 nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link @isroute('product.show') active @endisroute"
               href="{{ route('product.show', $product) }}#productsmenu">Item Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @isroute('product.rules') active @endisroute"
               href="{{ route('product.rules', $product) }}#productsmenu">Payment Rules</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @isroute('product.feedback') active @endisroute"
               href="{{ route('product.feedback', $product) }}#productsmenu">Product Feedback</a>
        </li>
        @if($product -> isPhysical())
            <li class="nav-item">
                <a class="nav-link @isroute('product.delivery') active @endisroute"
                   href="{{ route('product.delivery', $product) }}#productsmenu">Delivery Method(s)</a>
            </li>
        @endif


    </ul>

    @yield('product-content')
@stop


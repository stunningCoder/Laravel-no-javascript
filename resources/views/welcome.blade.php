@extends('master.main')

@section('title','Home Page')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

    {{--    @include('includes.search')--}}

    <div class="row">

        <div class="col-md-3 col-sm-12" style="margin-top:2.3em">
            @include('includes.categories')

{{--            <div class="row mt-3">
                <div class="col">
                    <div class="card ">
                        <div class="card-header">
                            R.M Official Mirrors
                        </div>
                        <div class="card-body text-center side_links">
                            @foreach(config('marketplace.mirrors') as $mirror)
                                <a href="{{$mirror}}" style="text-decoration:none;">{{$mirror}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>--}}

            <div class="row mt-3">
                @include('includes.detailedsearch')
            </div>

        </div>

        <div class="col-md-9 col-sm-12 mt-3 ">

            <div class="row mb-2">
                <div class="col">
                    <h3 class="col-10 vn"><b>Let today be the start of something new.</b>
                        Welcome to the {{config('app.name')}}!</h3>
                    <hr>
                </div>
            </div>

            @isModuleEnabled('FeaturedProducts')
            @include('featuredproducts::frontpagedisplay')
            @endisModuleEnabled

            <div class="row mb-5">

                <div class="col-md-12">
                    <h4 class="vn">Latest orders</h4>
                    <hr>
                    <div class="row mt-2">
                    @foreach(\App\Purchase::latestOrders() as $order)

                        @php
                            $product = $order->offer->product;
                        @endphp
                        <div class="col-md-4 col-sm-6 mb-2">
                            @include('includes.product.card',$product)
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-md-12">
                    <h4 class="vn">Top Vendors</h4>
                    <hr>

                    <table class="table table-borderless table-hover">
                    @foreach(\App\Vendor::topVendors() as $vendor)
                        @php
                            $userCoins = array_column(\App\Address::where('user_id', $vendor->user->id)->get()->toArray(), 'coin');
                            $salesCount =  $vendor->salesCount('delivered');
                        @endphp

                            <tr>
                                <td>
                                    <a href="{{route('vendor.show',$vendor)}}" class="top_vendor"
                                        style="text-decoration: none; color:#22913e">
                                        @if(Cache::has('is_online' . $vendor->user->id))
                                            <span class="useronline">●</span>
                                        @else
                                            <span class="useroffline">●</span>
                                        @endif
                                        {{$vendor->user->id.'   '}}
                                        {{$vendor->user->username}}
                                    </a>
                                </td>
                                <td>
                                    <i class="fab fa-bitcoin"></i>
                                    <i class="fab fa-bitcoin"></i>
                                    <i class="fab fa-bitcoin"></i>
                                    <i class="fab fa-bitcoin"></i>
                                </td>
                                <td>
                                    {{ $vendor->getNumberOfItemsForSale() }} {{ Str::plural('item', $salesCount) }} for sale
                                </td>
                                <td>
                                    {{ $salesCount}} {{ Str::plural('order', $salesCount) }} Completed
                                </td>
                                <td>
                                    {{ $vendor->countFeedback() }} Feeddback
                                </td>

                                @if(!empty($vendor->vendor))
                                    <td class="text-right">
                                     <span class="btn btn-sm @if($vendor->vendor->experience >= 0) btn-primary @else btn-danger @endif active"
                                           style="cursor:default">Level {{$vendor->getLevel()}}</span>

                                    </td>
                                @endif
                            </tr>

                    @endforeach
                    </table>

                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-md-12">
                    <h4 class="vn">
                        Rising vendors
                    </h4>
                    <hr>

                        <table class="table table-borderless table-hover">
                    @foreach(\App\Vendor::risingVendors() as $vendor)
                                <tr>
                                    <td>
                                        <a href="{{route('vendor.show',$vendor)}}" class="rising_vendor"
                                           style="text-decoration: none; color:#22913e">{{$vendor->user->username}}</a>
                                    </td>
                                    <td>
                                        {{ strtoupper(implode(' / ', $userCoins)) }}
                                    </td>
                                    <td>
                                        {{ $vendor->getNumberOfItemsForSale() }} {{ Str::plural('item', $salesCount) }} for sale
                                    </td>
                                    <td>
                                        {{ $salesCount}} {{ Str::plural('order', $salesCount) }} Completed
                                    </td>
                                    <td>
                                        {{ $vendor->countFeedback() }} Feeddback
                                    </td>
                                @if(!empty($vendor->vendor))
                                    <td class="text-right">
                                       <span class="btn btn-sm @if($vendor->vendor->experience >= 0) btn-primary @else btn-danger @endif active"
                                             style="cursor:default">Level {{$vendor->getLevel()}}</span>
                                    </td>
                                @endif
                        </table>
                            </tr>
                    @endforeach

                </div>


            </div>


        </div>

    </div>

@stop


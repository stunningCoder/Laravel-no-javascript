@extends('master.profile')

@section('title')
    @yield('purchase-title')
@stop

@section('profile-content')

    <div class="row">
        <div class="col-md-7">
            @include('includes.flash.success')
            @include('includes.flash.error')
            @include('includes.validation')
            <h3 class="mb-3">@yield('purchase-title') - @include('includes.currency', ['usdValue' => $purchase -> value_sum ])</h3>
            <p class="text-muted">Created {{ $purchase -> timeDiff() }} - {{ $purchase -> created_at }}</p>
        </div>

    </div>

@php

@endphp

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
        @media(min-width : 992px ){
            div#pslider {
                width: auto !important;
            }
        }
    </style>
    <div class="row">
        <div class="col-md-5">
            <div class="slider" id="pslider">
                <div class="slides">
                    @php $i = 1; @endphp
                    @foreach($purchase->offer->product -> images() -> orderBy('first', 'desc') -> get() as $image)
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
                @foreach($purchase->offer->product -> images as $image)
                    <a href="#slide-{{ $i }}">{{ $i++ }}</a>
                @endforeach
            </div>
        </div>

        <div class="col-md-7">

            <h2>{{ $purchase->offer->product-> name }}</h2>
            <hr>

            <div class="row">
                <div class="col-md-12 text-left">



                        <table class="table border-0 text-left table-borderless">
                            <tbody>
                            <tr>
                                <td>
                                    <h4>Type</h4>
                                    <p><strong class="badge badge-info">{{ ucfirst($purchase->offer->product->type) }}</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Description</h4>
                                    <p>{!! Markdown::parse(nl2br(e( $purchase->offer->product->description))) !!}</p>
                                </td>
                            </tr>


                            </tbody>
                        </table>

                    </form>


                </div>


            </div>
        </div>
    </div>


    @if($purchase->status_notification !== null)
        <div class="row">
            <div class="col">
                <div class="alert alert-danger">
                    {{$purchase->status_notification}}
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        @include('includes.purchases.components.offer')
        @include('includes.purchases.components.delivery')
    </div>
    <div class="row">
    @if(!$purchase->isBuyer())
        @include('includes.purchases.components.message')
    @endif
        @include('includes.purchases.components.payment')
    </div>
    <div class="row">
        @include('includes.purchases.components.feedback')
    </div>
    <div class="row">
        @include('includes.purchases.components.dispute')
    </div>


@stop

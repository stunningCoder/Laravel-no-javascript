<div class="row my-2 product-col">

    <div class="col-md-3 col-sm-4 col-12">
        <a href="{{ route('product.show', $product) }}">
            {!! $product->isFreeDelivery()  !!}

            @php
                $img = asset('storage/' . $product->frontImage()->image);
                //  $thumb = asset('storage/products/thumbnail/' . str_replace('products/', '', $product->frontImage()->image));
            @endphp

            <img class="rounded img-thumbnail mw-100" src="{{ $img }}" alt="{{ $product -> name }}">
        </a>
    </div>

    <div class="col-md-9 col-sm-8 col-12">
        <div class="row pb-2 mb-2 border-bottom border-light">
            <div class="col-md-8">
                <h3 class="mb-0 text-left"><a class="product_name" href="{{ route('product.show', $product) }}">{{ $product -> name }}</a></h3>
            </div>
            <div class="col-md-4">
                <h5 class="mb-0 text-right">Vendor:@if(Cache::has('is_online' . $product -> user->id)) <span class="useronline">●</span> @else <span class="useroffline">●</span> @endif <a href="{{ route('vendor.show', $product -> user) }}" class="red_link underline">{{ $product -> user -> username }}</a></h5>
            </div>
        </div>

        <div class="row row_product">
            <div class="col-md-7 text-left">
                From: <strong>@include('includes.currency', ['usdValue' => $product -> price_from ])</strong>
                <br>
                <span class="mr-2">Category:</span>
                @foreach($product -> category -> parents() as $ancestor)
                    <a class="red_link underline" href="{{ route('category.show', $ancestor) }}">{{ $ancestor -> name }}</a>
                    <i class="fas fa-chevron-right"></i>
                @endforeach
                <a class="red_link underline" href="{{ route('category.show', $product -> category) }}">{{ $product -> category -> name }}</a>
                <br>
                Type: <span class="badge badge-info">{{ ucfirst($product -> type) }}</span>
                <br>
                <strong>{{ $product -> quantity }}</strong> left / <strong>{{ $product -> orders }}</strong>
                sold
                @if($product->isPhysical())
                    <p class="card-subtitle" style="margin-top:5px;">Shipping From:
                        {!! $product->physical->shipsFrom() !!}
                    </p>
                @endif
            </div>

            <div class="col-md-5">
                <p class="text-muted">{!! Markdown::parse(nl2br(e( $product -> short_description ))) !!}</p>
                <p> Payment coins:
                    @foreach($product -> getCoins() as $coin)
                        @if($coin == 'btc')
                            <i class="fab fa-bitcoin"></i>

                        @endif
                        @if($coin == 'xmr')
                            <i class="fab fa-monero"></i>
                        @endif
                        @if($coin == 'stb')
                            <i class="fas fa-coins"></i>
                        @endif

                    @endforeach
                </p>
                <a href="{{ route('product.show', $product) }}" class="btn btn-primary d-block">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Buy now
                </a>
            </div>

        </div>

    </div>


</div>

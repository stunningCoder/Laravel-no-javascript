<div class="card product_container">
    <a href="{{ route('product.show', $product) }}">
        {!! $product->isFreeDelivery()  !!}
        <img class="card-img-top" src="{{ asset('storage/'  . $product -> frontImage() -> image) }}" alt="{{ $product -> name }}">
{{--        @if($product->isPhysical())--}}
{{--            {!! $product->physical->getShippingFromToDetails() !!}--}}
{{--        @endif--}}
    </a>
    <div class="card-body">
        <a href="{{ route('product.show', $product) }}" title="{{ $product -> name }}">
            <h4 class="card-title">
                @php
                    if (strlen($product->name) > 65)
                    {
                       echo substr($product->name, 0, 65) . '...';

                    } else {
                        echo $product->name;

                    }
                @endphp
            </h4>
        </a>
        <p class="card-subtitle">From: <strong>{{\App\Marketplace\Utility\CurrencyConverter::getSymbol()}}{{ $product->getLocalPriceFrom() }} {{\App\Marketplace\Utility\CurrencyConverter::getLocalCurrency()}}</strong> - {{ $product -> category -> name }} - <span class="badge badge-info">{{ $product -> type }}</span></p>
        <p class="card-text">
            Vendor: @if(Cache::has('is_online' . $product -> user->id)) <span class="useronline">●</span> @else <span class="useroffline">●</span> @endif <a href="{{ route('vendor.show', $product -> user) }}" class="badge badge-info">{{ $product -> user -> username }}</a>, <strong>{{ $product -> quantity }}</strong> left
        </p>
        <p class="card-subtitle">Accepts:
            <span>
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
            </span>
        </p>
        @if($product->isPhysical())
            <p class="card-subtitle" style="margin-top:5px;">Shipping From:
                {!! $product->physical->shipsFrom() !!}
            </p>
        @endif
        <br>
        <a href="{{ route('product.show', $product) }}" class="btn btn-primary d-block">Buy now</a>
    </div>
</div>

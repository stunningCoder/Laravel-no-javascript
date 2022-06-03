@extends('includes.profile.offersform')

@section('digital-form-next-button')

    <form method="POST" action="{{ route('profile.vendor.product.digital.post', session('product_details') ?? new App\DigitalProduct() ) }}">
        {{ csrf_field() }}

        <div class="form-row justify-content-center">
            <div class="form-group col-md-3 text-center">
                @if(request() -> is('profile/vendor/product/edit/*'))
                    <a href="{{ route('profile.vendor.product.edit', [$basicProduct, 'images']) }}"
                       class="btn btn-outline-primary"><i class="fas fa-chevron-down mr-2"></i> Next</a>
                @elseif(request() -> is('admin/product/*'))
                    <a href="{{ route('admin.product.edit', [$basicProduct, 'images']) }}"
                       class="btn btn-outline-primary"><i class="fas fa-chevron-down mr-2"></i> Next</a>
                @else
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-chevron-down mr-2"></i> Next</button>
                @endif
            </div>
        </div>

    </form>
@endsection

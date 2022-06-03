@extends('includes.profile.addingform')

@section('form-content')
    <form method="POST" action="{{ route('profile.vendor.product.add.post', optional($basicProduct) -> exists ? $basicProduct : null) }}">
        {{ csrf_field() }}
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Product's name:</label>
                <input type="text" class="form-control @error('name', $errors) is-invalid @enderror" id="name"
                       name="name" placeholder="Product name"  maxlength="100"
                       @if(isset(optional($basicProduct) -> name))
                            value="{{ optional($basicProduct) -> name}}"

                       @else
                            value="{{old('name') }}"
                       @endif
                       maxlength="100">
                @error('name', $errors)
                <div class="invalid-feedback d-block text-center">
                    {{ $errors -> first('name') }}
                </div>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="name">Product's category {{$product_type}}:</label>
                <select class="form-control " name="category_id">
                @if(!empty($categories))
                    @foreach($categories as $k=>$cat)


                                <optgroup label="{{ $cat->name }}">

                                        @if($cat->children->isNotEmpty())

                                            @foreach($cat->children as $k=>$subcat)
                                                <option value="{{ $subcat -> id }}" @if($subcat -> id == optional($basicProduct) -> category_id || $subcat -> id == old('category_id')) selected @endif>{{ $subcat -> name }}</option>
                                            @endforeach

                                        @endif

                                </optgroup>


                    @endforeach
                @endif
                </select>
                @error('category_id', $errors)
                <div class="invalid-feedback d-block text-center">
                    {{ $errors -> first('category_id') }}
                </div>
                @enderror
            </div>
        </div>

{{--        @if(request() -> is('profile/vendor/product/edit/*'))
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="active">Status</label>
                    <select name="active" id="active" class="form-control">
                        <option selected disabled>Select Status</option>
                        <option value="1" {{ ($basicProduct->active == 1 || old('active') == 1) ? 'selected' : '' }}>Publish</option>
                        <option value="0" {{ ($basicProduct->active == 0 || old('active') == 0) ? 'selected' : '' }}>inactive</option>
                    </select>
                </div>
            </div>
        @endif--}}

        <div class="form-group">
            <label for="description">Product's description:</label>
            <textarea name="description" id="description"
                      class="form-control @error('description', $errors) is-invalid @enderror" rows="20"
                      placeholder="Details about the product">@if(isset(optional($basicProduct)->description)){{ optional($basicProduct)->description }}@else{{old('description') }}@endif</textarea>
            <p>
                <i class="fab fa-markdown"></i> Styling with Markdown is supported
            </p>
            @error('description', $errors)
            <div class="invalid-feedback d-block text-center">
                {{ $errors -> first('description') }}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="rules">Payment rules:</label>
            <textarea name="rules" id="rules" class="form-control @error('rules', $errors) is-invalid @enderror"
                      rows="10"
                      placeholder="Rules of conducting business">@if(isset(optional($basicProduct)->rules)){{ optional($basicProduct)->rules }}@else{{old('rules')}}@endif</textarea>
            <p>
                <i class="fab fa-markdown"></i> Styling with Markdown is supported
            </p>
            @error('rules', $errors)
            <div class="invalid-feedback d-block text-center">
                {{ $errors -> first('rules') }}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="coins">Supported types:</label>
            <select name="types[]" id="types" multiple class="form-control">
                @foreach(\App\Purchase::$types as $type => $typeLongName)
                    @if(auth()->user()->vendor->canUseType($type))
                        <option value="{{ $type }}" {{ optional($basicProduct) -> supportsType($type)  || ( is_array(old('types')) && in_array($type,old('types')) ) ? 'selected' : '' }}>{{ $typeLongName }}</option>
                    @endif
                @endforeach
            </select>
            @error('types', $errors)
            <div class="invalid-feedback d-block text-center">
                {{ $errors -> first('types') }}
            </div>
            @enderror
        </div>


        <div class="form-group">
            <label for="coins">Supported coins:</label>
            <select name="coins[]" id="coins" multiple class="form-control">
                @foreach(config('coins.coin_list') as $coin => $instance)
                    <option value="{{ $coin }}" {{ optional($basicProduct) -> supportsCoin($coin) ||  (is_array(old('coins')) && in_array($coin,old('coins')) ) ? 'selected' : '' }}>{{ strtoupper(\App\Purchase::coinDisplayName($coin)) }}</option>
                @endforeach
            </select>
            @error('coins', $errors)
            <div class="invalid-feedback d-block text-center">
                {{ $errors -> first('coins') }}
            </div>
            @enderror
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control @error('quantity', $errors) is-invalid @enderror"
                       @if(optional($basicProduct) -> isAutodelivery()) readonly @endif
                       name="quantity" id="quantity" min="1" placeholder="Number of products"

                       @if(isset(optional($basicProduct) -> quantity))
                            value="{{ optional($basicProduct) -> quantity }}"
                       @else
                            value="{{old('quantity') }}"
                      @endif
                >
                @error('quantity', $errors)
                <div class="invalid-feedback d-block text-center">
                    {{ $errors -> first('quantity') }}
                </div>
                @enderror
                @if(optional($basicProduct) -> isAutodelivery())
                    <p class="text-muted">The product is marked as autodelivery, you can't change quantity manually.</p>
                @endif
            </div>
            @if($product_type != 'digital')
                <div class="form-group col-md-6">
                    <label for="mesure">Measure</label>
                    <input type="text" maxlength="10" class="form-control @error('mesure', $errors) is-invalid @enderror"
                           id="mesure" name="mesure" placeholder="Unit of mesure(item, gram)"


                           @if(isset(optional($basicProduct) -> mesure))
                                value="{{ optional($basicProduct) -> mesure }}"
                           @else
                                value="{{old('mesure') }}"
                           @endif
                    >
                    @error('mesure', $errors)
                    <div class="invalid-feedback d-block text-center">
                        {{ $errors -> first('mesure') }}
                    </div>
                    @enderror
                </div>
            @else
                <input type="hidden" name="mesure" value="1" />
            @endif
        </div>
        <div class="form-row justify-content-center">
            <div class="form-group col-md-3 text-center">
                @if(request() -> is('profile/vendor/product/edit/*'))
                    <button class="btn btn-outline-success" type="submit"><i class="far fa-save mr-2"></i> Save</button>
                    <a href="{{ route('profile.vendor.product.edit', [$basicProduct, 'offers']) }}"
                       class="btn btn-outline-primary"><i class="fas fa-chevron-down mr-2"></i> Next</a>
                @elseif(request() -> is('admin/product/*'))
                    <button class="btn btn-outline-success" type="submit"><i class="far fa-save mr-2"></i> Save</button>
                    <a href="{{ route('admin.product.edit', [$basicProduct, 'offers']) }}"
                       class="btn btn-outline-primary"><i class="fas fa-chevron-down mr-2"></i> Next</a>
                @else
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-chevron-down mr-2"></i> Next
                    </button>
                @endif
            </div>
        </div>
    </form>
@stop

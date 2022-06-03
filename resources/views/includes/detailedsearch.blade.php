<div class="row">
    <div class="col mt-2">
        <div class="card mt-5">
            <div class="card-header">
                <h4 class="side-heading" style="font-size: 1rem;">Advanced Search</h4>
            </div>
            <div class="card-body">
                <form action="{{route('search')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
{{--                        <label for="search">Search terms:</label>--}}
                        <input type="text" name="search" id="search" class="form-control" placeholder="Type your search..." value="{{app('request')->input('query')}}">
                    </div>
@if(!empty(app('request')->input('user')))
                    <div class="form-group">
{{--                        <label for="user">User:</label>--}}
                        <input type="text" name="user" id="user" class="form-control" value="{{app('request')->input('user')}}">
                    </div>
@endif
                    <div class="form-group">
{{--                        <label for="category">Category:</label>--}}
                        <select class="form-control" id="category" name="category">
                            <option selected value="any">Search Categories (All)</option>
                            @foreach($categories as $category)
                                <option value="{{$category->name}}" @if(app('request')->input('category') == $category->name) selected @endif>{{$category->name}}</option>
                                @if($category -> children -> isNotEmpty())
                                    @foreach($category->children as $child)
                                        <option value="{{$child->name}}" @if(app('request')->input('category') == $child->name) selected @endif> > {{$child->name}}</option>
                                        @if($child -> children -> isNotEmpty())
                                            @foreach($child->children as $subChild)
                                                <option value="{{$subChild->name}}" @if(app('request')->input('category') == $subChild->name) selected @endif> >> {{$subChild->name}}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
{{--                        <label for="purchase_type">Relevance:</label>--}}
                        <select class="form-control" id="purchase_type" name="purchase_type">
                            <option selected value="any">Relevance (Any)</option>
                            @foreach(\App\Purchase::$types as $k => $pt)
                                <option value="{{$k}}" @if(app('request')->input('purchase_type') == $k) selected @endif>{{$pt}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
{{--                        <label for="shipping_from">Shipping From:</label>--}}
                        <select class="form-control" id="from_country_code" name="from_country_code">
                            <option selected value="any">Shipping From (Any)</option>
                            @foreach(config('countries') as $countryShort => $countryName)
                                <option value="{{ $countryShort }}" @if(app('request')->input('from_country_code') == $countryShort) selected @endif>{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
{{--                        <label for="shipping_to">Shipping To :</label>--}}
                        <select class="form-control" id="to_country_code" name="to_country_code">
{{--                            <option selected value="any">Shipping To (Any)</option>--}}
                            <option selected value="all">Shipping To All</option>
                            @foreach(config('countries') as $countryShort => $countryName)
                                <option value="{{ $countryShort }}" @if(app('request')->input('to_country_code') == $countryShort) selected @endif>{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" id="coins" name="coins">
                            <option selected value="any">Payment Currency (Any)</option>
                            @foreach(config('coins.coin_list') as $coin => $instance)
                                <option value="{{ $coin }}" {{  ( app('request')->input('coins') == $coin ) ? 'selected' : '' }}>
                                    {{ strtoupper(\App\Purchase::coinDisplayName($coin)) }}</option>
                            @endforeach
                        </select>
                    </div>



{{--                    <div class="form-group">
                        <label for="product_type">Product type:</label>
                        <select class="form-control" id="product_type" name="product_type">
                            <option selected value="all">All</option>
                            <option value="digital" @if(app('request')->input('type') == 'digital') selected @endif>Digital</option>
                            <option value="physical" @if(app('request')->input('type') == 'physical') selected @endif>Physical</option>
                        </select>
                    </div>--}}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="number" name="minimum_price" id="" placeholder="Minimum price" class="form-control" value="{{app('request')->input('price_min')}}" step="0.01">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="number" name="maximum_price" id="" placeholder="Maximum price" class="form-control" value="{{app('request')->input('price_max')}}" step="0.01">
                        </div>
                    </div>

{{--                    <div class="form-group">
                        <label for="">Order By:</label>
                        <select class="form-control" id="order_by" name="order_by">
                            <option @if(app('request')->input('order_by') == 'price_asc' ||app('request')->input('order_by') == null) selected @endif value="price_asc">Price: Low to High</option>
                            <option @if(app('request')->input('order_by') == 'price_desc') selected @endif value="price_desc">Price: High to Low</option>
                            <option @if(app('request')->input('order_by') == 'newest') selected @endif value="newest">Newest</option>
                        </select>
                    </div>--}}
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search Now</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

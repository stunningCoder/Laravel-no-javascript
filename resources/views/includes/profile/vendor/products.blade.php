<h3 class="mt-3">My products</h3>
<hr>

@if(auth() -> user() -> products -> isNotEmpty())

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Status</th>
                <th>Title</th>
                <th>Quantity</th>
                <th>Coins</th>
                <th>Price from</th>
                <th>Sales</th>
                <th>Category</th>
                <th>Type</th>

                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($myProducts as $product)
                <tr>
                    <td>
                        <a href="{{ route('profile.vendor.product.status.show', $product ) }}">
                            {!! ($product->active == 1) ? '<span class="badge" style="background: green;color:#fff">Published</span>' : '<span class="badge" style="background: rgb(255, 20, 3);color:#fff">inactive</span>' !!}
                        </a>
                    </td>
                    <td><a href="{{ route('product.show', $product) }}">{{ $product -> name }}</a></td>
                    <td class="text-right">{{ $product -> quantity }}</td>
                    <td>{{ $product->coins }}</td>
                    <td class="text-right">@include('includes.currency', ['usdValue' => $product -> price_from ])</td>
                    <td>{{ $product->getOrdersAttribute() }}</td>
                    <td><a href="{{ route('category.show', $product -> category) }}">{{ $product -> category -> name }}</a></td>
                    <td><span class="badge badge-info">{{ $product -> isDigital() ? 'Digital' : 'Physical' }}</span></td>


                    <td class="text-right">
                        <a href="{{ route('profile.vendor.product.clone.show', $product ) }}" class="btn btn-sm btn-info">
                            Clone
                        </a>
                        <a href="{{ route('profile.vendor.product.edit', $product -> id) }}" class="btn btn-sm btn-primary">
                            <i class="far fa-edit"></i>
                        </a>
                        <a href="{{ route('profile.vendor.product.remove.confirm', $product -> id) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{{ $myProducts -> links('includes.paginate') }}}

@else
    <div class="alert alert-warning text-center">
        You don't have any products!
    </div>
@endif

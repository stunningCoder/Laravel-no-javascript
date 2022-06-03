@foreach($categories as $cat)
    <div class="category">
        <a href="{{ route('category.show', $cat) }}" class="list-group-item category @if(isset($category) && $cat -> isAncestorOf(optional($category))) active @endif  rounded-0 list-group-item-action d-flex justify-content-between">
            {{ $cat -> name }}
            <span class="badge">( {{ $cat -> num_products }} )</span>
        </a>
        @if($cat -> children -> isNotEmpty())
            <div class="pl-3 subcategories">
                @include('includes.subcategories', ['categories' => $cat -> children])
            </div>
        @endif
    </div>
@endforeach

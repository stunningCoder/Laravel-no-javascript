<ul>
    @foreach($categories as $category)
        @if($category -> is_physical == $is_physical)
        <li>
            <a href="{{ route('admin.categories.show', $category -> id) }}">{{ $category -> name }}</a>
            <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.categories.delete', $category -> id) }}">Delete</a>
            @if($category -> children -> isNotEmpty())
                <ul class="m-0 p-0">
                    @include('includes.admin.listcategories', ['categories' => $category -> children])
                </ul>
            @endif
        </li>
        @endif
    @endforeach
</ul>

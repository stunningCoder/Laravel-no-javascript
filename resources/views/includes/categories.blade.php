<h4 class="side-heading">Categories</h4>
<div class="list-group categories">
    <div class="text-center"><a href="{{route('category.selectAll')}}">Select All</a> / <a href="{{route('category.deselectAll')}}">Deselect All</a></div>
    <div class="border-bottom border-secondary m-3 "></div>
    <div class="treeview">
        @include('includes.subcategories', ['categories' => $categories,'level'=>""])
    </div>
</div>

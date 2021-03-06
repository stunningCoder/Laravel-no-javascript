@extends('master.admin')

@section('admin-content')

    @include('includes.flash.success')
    @include('includes.flash.error')
    <h1 class="mb-5">Categories</h1>

    <div class="row">
        <div class="col-md-6">
            <h3>Edit category - <em>{{ $category -> name }}</em></h3>
            <hr>
            <form action="{{ route('admin.categories.edit', $category -> id) }}"  method="POST">
                {{ csrf_field() }}
                <label for="name">Category name</label>
                <input name="name" id="name" placeholder="Category name" value="{{ $category -> name }}" class="form-control mb-3 @error('name', $errors) is-invalid @enderror"/>
                @error('name', $errors)
                <div class="invalid-feedback d-block">{{ $errors -> first('name') }}</div>
                @enderror


                <div>
<!--                    $category->is_physical = {{ $category->is_physical  }} -->

                    <label for="digital" class="@error('is_physical', $errors) is-invalid @enderror">
                        <input type="radio" id="digital" name="is_physical" value="0" @if($category->is_physical == 0) checked @endif />
                        Digital Item</label>

                    <label for="physical" class="@error('is_physical', $errors) is-invalid @enderror">
                        <input type="radio" id="physical" name="is_physical" value="1" @if($category->is_physical == 1) checked @endif />
                        Physical Item</label>


                    @error('is_physical', $errors)
                    <div class="invalid-feedback d-block">{{ $errors -> first('is_physical') }}</div>
                    @enderror
                </div>

                <label for="parent_id">Parent category:</label>
                <select name="parent_id" class="form-control mb-3" id="parent_id">
                    <option value="" @if($category -> parent_id == null) selected @endif>No parent category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat -> id }}" @if($category -> parent_id == $cat -> id) selected @endif>{{ $cat -> name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-success d-flex float-right" type="submit">Update category</button>
            </form>
        </div>
        <div class="col-md-6">
            @if($category -> parent)
                <h3>Parent Category</h3>
                <hr>
                <a href="{{ route('admin.categories.show', $category -> parent -> id) }}"><strong>{{ $category -> parent -> name }}</strong></a>
            @endif
            @if($category -> children -> isNotEmpty())
                <h3 class="mt-3">Subcategories of this category</h3>
                <hr>
                @include('includes.admin.listcategories', ['categories' => $category -> children])
            @endif
        </div>
    </div>


@stop

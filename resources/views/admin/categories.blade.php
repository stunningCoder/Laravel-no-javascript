@extends('master.admin')

@section('admin-content')

    @include('includes.flash.success')
    @include('includes.flash.error')
    <h3 class="mb-5">Categories ({{ count($categories) }})</h3>

    <div class="row">
        <div class="col-md-6">
{{--            ({{ count($categories) }})--}}
            <h4>List of Physical categories</h4>
            <hr>
            @if($rootCategories -> isNotEmpty())
                @include('includes.admin.listcategories', ['categories' => $rootCategories , 'is_physical' => 1])
            @else
                <div class="alert alert-warning text-center">There are no  Physical categories!</div>
            @endif
        </div>
        <div class="col-md-6">
            <h4>Add new category</h4>
            <hr>

            <form action="{{ route('admin.categories.new') }}"  method="POST">
                {{ csrf_field() }}

                <input name="name" placeholder="Category name" class="form-control mb-3 @error('name', $errors) is-invalid @enderror"
                       @if(isset($request)) value="{{ old('name') }}" @endif
                />
                @error('name', $errors)
                <div class="invalid-feedback d-block">{{ $errors -> first('name') }}</div>
                @enderror


                <div>

                    <div>
                        <label for="digital" class="@error('is_physical', $errors) is-invalid @enderror">
                            <input type="radio" id="digital" name="is_physical" value="0" @if(isset($request) && $request->old('is_physical') == 0) checked @endif  />
                            Digital Item</label>
                    </div>

                    <div>
                        <label for="physical" class="@error('is_physical', $errors) is-invalid @enderror">
                            <input type="radio" id="physical" name="is_physical" value="1" @if(isset($request) && $request->old('is_physical') == 1) checked @endif />
                            Physical Item</label>
                    </div>


                    @error('is_physical', $errors)
                    <div class="invalid-feedback d-block">{{ $errors -> first('is_physical') }}</div>
                    @enderror

                </div>



                <label for="parent_id">Parent category:</label>
                <select name="parent_id" class="form-control mb-3" id="parent_id">
                    <option value="" selected>No parent category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category -> id }}" @if(isset($request) && $request->old('parent_id') == $category->id) selected @endif>{{ $category -> name }}</option>
                    @endforeach
                </select>

                <button class="btn btn-outline-success d-flex float-right" type="submit">Add category</button>

            </form>

        </div>

        <div class="col-md-6">
            <h4>List of digital categories</h4>
            <hr>
            @if($rootCategories -> isNotEmpty())
                @include('includes.admin.listcategories', ['categories' => $rootCategories , 'is_physical' => 0])
            @else
                <div class="alert alert-warning text-center">There are no digital categories!</div>
            @endif
        </div>

    </div>


@stop

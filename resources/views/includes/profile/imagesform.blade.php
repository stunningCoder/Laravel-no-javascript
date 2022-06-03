@extends('includes.profile.addingform')


@section('form-content')
<h3>Add image</h3>
<p>Minimum dimensions 480x480px and maximum file size of 2.5MB</p>
<hr>
<form action="{{ route('profile.vendor.product.images.post', $basicProduct) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <input type="file" class="form-control border-0" name="picture[]" id="picture" multiple />
    </div>
    <div class="form-inline">
  {{--      @if(empty($productsImages))
            <input type="checkbox" value="1" name="first" checked style="display: none">
        @endif--}}
{{--        <div class="form-check mx-2 mb-2 ">

            <label class="form-check-label" for="defaultcheck">
                Default product image
            </label>
        </div>--}}

        @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach

        <button type="submit" class="btn btn-primary mb-2">Add image</button>
    </div>
</form>



<h3 class="mt-3">Images of the product</h3>
<hr>
<p class="text-muted">Default picture is marked with red.</p>

@if(!empty($productsImages ?? []))


<div class="card-columns img_container">
    @foreach($productsImages as $image)
        <div class="card my-3 p-2 @if($image -> first) bg-success @endif uploadimg" data-first="{{$image -> first}}">
            @php
            $img = asset('storage/' . $image -> image);
         //   $thumb = asset('storage/products/thumbnail/' . str_replace('products/', '', $image -> image));
            @endphp

                <img class="card-img" src="{{ $img }}" alt="">

            <div class="card-img-overlay text-center">
                @if(!$image -> first)
                    @if(count($productsImages) > 1)
                        <a href="{{ route('profile.vendor.product.images.default', $image -> id) }}" class="btn-sm btn-primary mr-4">Default</a>
                    @endif
                    <a href="{{ route('profile.vendor.product.images.remove', $image -> id) }}" class="btn-sm btn-danger"><i class="far fa-trash-alt"></i> Delete</a>
                @else
                    @if(count($productsImages) > 1)
                        <p class="bg-white text-muted">Default picture</p>
                    @endif

                    <a href="{{ route('profile.vendor.product.images.remove', $image -> id) }}" class="btn-sm btn-danger"><i class="far fa-trash-alt"></i> Delete</a>
                @endif

                <a href="{{ $img }}" target="_blank" class="btn-sm btn-secondary" title="Click to view original image in new window"><i class="fa fa-eye"></i></a>
            </div>
        </div>
    @endforeach
</div>

    <style>
        .uploadimg{
            width: 220px !important;
            height: 220px !important;
            }

        .uploadimg:hover img{
            -o-object-position:contain;
            object-position:contain;
            -o-object-fit:contain;
            object-fit:contain;
            }

        .uploadimg img{
            width: 100%;
            height: 100%;
            -o-object-position:cover;
            object-position:cover;
            -o-object-fit:cover;
            object-fit:cover;
            }
    </style>

@else
    <div class="col-12 text-center alert alert-warning">
        You don't have any images added, it must be at least one!
    </div>
@endif

@stop

@extends('master.product')

@section('product-content')


    <p>{!! Markdown::parse(nl2br(e($product -> rules))) !!}</p>


@stop
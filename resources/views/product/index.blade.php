@extends('master.product')

@section('product-content')

{!! Markdown::parse(nl2br(e($product -> description))) !!}

@stop
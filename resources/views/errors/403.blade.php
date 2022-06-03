@extends('master.main')

@section('title','Error hapend')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include('includes.flash.error')
        </div>
    </div>

    <div class="text-center">
        <h1 class="text-danger my-3">It's 404, we looked everywhere in our dark archive :(</h1>
        <p class="display-4">It's not the end of the world, the link you typed appears to be broken. We've got tons of stuff, you gotta keep on rolling Ted!</p>
    </div>

@stop

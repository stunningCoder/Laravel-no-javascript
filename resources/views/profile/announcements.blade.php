@extends('master.profile')

@section('title', 'Announcements')

@section('profile-content')
    @include('includes.flash.success')
    @include('includes.flash.error')

    <h1 class="mb-3">Cave Announcements.</h1>
    <div class="row">
        {!! $data[0]->body !!}
    </div>

@stop

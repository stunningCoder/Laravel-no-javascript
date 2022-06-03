@extends('master.profile')

@section('profile-content')
    @include('includes.flash.success')

    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-3">PGP Keys.</h1>

            <br>

        </div>
        @if(auth()->user()->isVendor() == true)
            <div class="col-md-12">
                <p class="mb-3" style="display: inline-block !important;width: 80%;">Enable "PGP" button on my profile for everyone to see my public PGP.
                <div class="col-md-12 text-right" style="display: inline">
                    <div class="btn-group" role="group">
                        <a href="{{ route('profile.pgp.status',['status' =>  true ]) }}" class="btn @if(auth()->user()-> getPgpPublicStatus() == true) btn_success @else btn-outline-grey @endif">On</a>
                        <a href="{{ route('profile.pgp.status',['status' =>  0 ])}}" class="btn @if(auth()->user()-> getPgpPublicStatus() == false) btn_red @else btn-outline-grey @endif">Off</a>
                    </div>
                </div>
                </p>
                <br>
            </div>
        @endif
        <div class="col-md-6">
            <h3 class="mb-3">Your PGP Key</h3>
            <hr>

            @if(auth() -> user() -> hasPGP())
                <p>Your ManCave PGP key is:</p>
                <textarea class="disabled form-control" style="resize: none" rows="10" disabled>{{{ auth() -> user() -> pgp_key }}}</textarea>
            @else
                <div class="alert alert-danger my-3">
                    You don't have any PGP key added on your ManCave Account. Please add one now!
                </div>
            @endif
            <p><a class="red_link underline" href="{{ route('profile.pgp.old') }}">View your PGP keys history</a></p>

        </div>
        <div class="col-md-6">
            <h3 class="mb-3">Set New PGP Key</h3>
            <hr>

            <form method="POST" action="{{ route('profile.pgp.post') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="newpgp">New PGP key:</label>
                    <textarea name="newpgp" id="newpgp" style="resize: none" rows="10" class="form-control @error('newpgp', $errors) is-invalid @enderror"></textarea>
                    @error('newpgp', $errors)
                    <div class="invalid-feedback">
                        {{ $errors -> first('newpgp') }}
                    </div>
                    @enderror
                    <p class="text-muted">Paste your PGP key here you'll confirm in the next step.</p>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary" type="submit">Add PGP Key!</button>
                </div>

            </form>
        </div>
    </div>




@stop


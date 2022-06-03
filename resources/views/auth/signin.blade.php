@extends('master.main')


@section('title','Sign in')

@section('content')

    <div class="row mt-5 justify-content-center">
        <div class="col-md-4">

            <h2>ManCave Market Sign in</h2>

            <div class="mt-3">
                <form action="{{ route('auth.signin.post') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input type="text" class="form-control @error('loginuser',$errors) is-invalid @enderror" placeholder="ManCave Username*" name="loginuser" id="loginuser"
                               value="{{ old('loginuser') }}" />
                        @error('loginuser',$errors)
                            <p class="text-danger">{{$errors->first('loginuser')}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('password',$errors) is-invalid @enderror" placeholder="ManCave Password*" name="password"
                               id="password" value="{{ old('password') }}" />
                        @error('password',$errors)
                        <p class="text-danger">{{$errors->first('password')}}</p>
                        @enderror
                    </div>
                    <div class="form-group text-center">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary btn-block">Log In</button>
                            </div>
                        </div>
                    </div>
                    @include('includes.flash.error')

                </form>
            </div>
                <div class="mt-3">
                    Forgot Password?
                    <a href="/forgotpassword" style="text-decoration: none">Reset <b>ManCave</b> Password!
                    </a>
                </div>
        </div>
    </div>


@stop


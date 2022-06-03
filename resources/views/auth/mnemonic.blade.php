@extends('master.main')


@section('title','Mnemonic')

@section('content')

    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <h2>Mnemonic Phrase!</h2>


            <div class="mt-3">
                <div class="form-group">
                    <p>
                        This is your mnemonic phrase for the ManCave Market. It consists out of <?php echo e(config('marketplace.mnemonic_length')); ?> words.
                        Please write them down and keep them somewhere safe. This is the only time you will see this, without a mnemonic phrase you cannot recover
                        your ManCave account in case you lose your password, nobody will be able to help you recover it.
                    </p>
                </div>
                <div class="form-group">
                    <textarea name="" id="" cols="30" rows="10" readonly class="form-control">{{$mnemonic}}</textarea>
                </div>
                <div class="form-group text-center">
                    <a href="{{route('auth.signin')}}" class="btn btn-warning">I have saved it, let's go!</a>
                </div>
            </div>

        </div>
    </div>


@stop

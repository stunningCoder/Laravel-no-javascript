@extends('master.profile')

@section('profile-content')

    @include('includes.flash.error')

    <h1 class="mt-5 mb-3">You Must Confirm Your PGP Key:</h1>
    <hr>
    <div class="form-group">
        <label for="decrypt_message">You can confirm that your PGP is working by decrypting the message below:</label>
        <textarea name="decrypt_message" id="decrypt_message" class="form-control " rows="10" style="resize: none;"  readonly>{{{ session() -> get(\App\Marketplace\PGP::NEW_PGP_ENCRYPTED_MESSAGE) }}}</textarea>
        <p class="text-muted">After Decrypting our encrypted message you will get a validation number, you will then enter that number in the box below to validate your PGP Key.</p>
    </div>
    <form method="POST" action="{{ route('profile.pgp.store') }}" class="form-inline">
        {{ csrf_field() }}
        <label for="validation_number">Validation number:</label>
        <input type="number" class="form-control mx-2" required name="validation_number" id="validation_number"/>
        <button class="btn btn-outline-success">Validate PGP</button>

    </form>
@stop
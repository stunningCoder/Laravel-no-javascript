
@extends('master.confirmationWithMessage')

@section('confirmation-title', 'Mark the sale as sent to "' . $sale->buyer->username . '"')

@section('confirmation-content')


    <div class="modal fade in show position-static d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark the sale as sent to "{{ $sale->buyer->username }}"</h5>
                    <a href="{{ $backRoute }}" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <form action="{{ route('profile.sales.sent.withmessage', $sale , null) }}" method="POST" class="my-2">
                <div class="modal-body">
                        {{ csrf_field() }}

                    @if (session()->has('private_rsa_key_decrypted'))
                        <textarea name="message" class="form-control" rows="5" style="background-color:#e9ecef;color: #000"
                                  placeholder="You can add an optional message for &quot;{{ $sale->buyer->username }}&quot; here amd ot will be sent to their inbox in a &quot;plain text&quot; do Not send any sensitive information in this box.

However, if you encrypt the message using &quot;{{ $sale->buyer->username }}&quot; pgp key that will be safe to send through this message box."
                        ></textarea>
                        <hr/>
                    @else
                        <a href="{{ $unlock_messages_url }}" class="btn btn-primary d-block" style="width: auto;">Add Message</a>
                    @endif


                        <p>This action can't be undone! Confirm that you have sent <strong>{{ $sale -> offer -> product -> name }}</strong> in quantity of <em>{{ $sale -> quantity }}</em>
                            <br>Purchase ID: {{ $sale -> short_id }}</p>


                </div>

                <div class="modal-footer text-center justify-content-center">
                    <a href="{{ $backRoute }}" class="btn btn-secondary" style="margin-left: auto;">Dismiss</a>
                    <button type="submit" class="btn btn-success" style="width: auto;">Confirm</button>
                </div>

                </form>
            </div>
        </div>
    </div>




@endsection


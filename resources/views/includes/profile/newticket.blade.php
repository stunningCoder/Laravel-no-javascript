<h3 class="mb-2">Raise A Support Ticket.</h3>

<form action="{{ route('profile.tickets.new') }}" method="POST">
    {{ csrf_field()  }}
    <div class="form-group">
        <br>
        <p>- If your messages don't include sensitive information then you not need to encrypt it, however; messages with sensite information can be encrypted with our <a href="http://mancavecurc4h3qjp6jt2iqxyk3dh3zeqsfrofqtecovq5hgrvgi5had.onion/pgpkey.txt" target="_blank">ManCave Public PGP Key</a>.</p>
        <br>
        @if(isset($reportedItem) && isset($reportedToUser) && isset($reportedByUser))
            <label for="title">Reported Item: </label>
            <input type="hidden" name="title" value="Report an item: {{ url('/product').'/'.$reportedItem }}" />
            <input disabled type="text" name="title" class="form-control" id="title" aria-describedby="title" placeholder="Ticket title cannot be encrypted so keep it descriptive"  value="Report an item: {{ url('/product').'/'.$reportedItem }}">
        @else
            <label for="title">Give your ticket a short title:</label>
            <input type="text" name="title" class="form-control" id="title" aria-describedby="title" placeholder="Ticket title cannot be encrypted so keep it descriptive"  value="{{ old('title') }}">
        @endif
    </div>
    <div class="form-group">
        <label for="text">Type in a brief yet comprehensive message:</label>
        <textarea class="form-control" name="message" id="title" rows="5" placeholder="Support messages are NOT encrypted by default, if you want to send us a message that includes sensitive information then you must encrypt your message with ManCave Public PGP Key, dont forget to include your own Public PGP Key for us to send you a secure reply.">{{ old('message') }}</textarea>
        <small class="form-text text-muted">Keep it short but ensure to include all the necessary details we will need to help you with your query!</small>
    </div>
       <br>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-outline-primary">
            Open ticket
            <i class="far ml-2 fa-plus-square"></i>
        </button>
    </div>
</form>



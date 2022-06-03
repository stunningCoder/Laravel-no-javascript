<?php
	/**
	 * @var $purchase App\Purchase
	 *
	 */
?>
{{-- Disputes --}}
<div class="col-md-12 mt-5 py-2" id="dispute">

<?php
       $isBuyer = $purchase->isBuyer();
       $isVendor = $purchase->isVendor();
       $notBuyerOrVendor = !$isBuyer && !$isVendor;
       $isModeratorDispute = $notBuyerOrVendor && ( auth()->user()->hasPermission('disputes') );
       $isModeratorAppeal = $notBuyerOrVendor && ( auth()->user()->hasPermission('disputeappeals') );
       $isAdmin = auth() -> user() -> isAdmin();
       $isDisputed = $purchase->isDisputed();
?>

  @if($isDisputed)

        <h3 class="mb-1">Dispute
            @if($purchase->dispute->isAllowedToEscalate() && ($isBuyer || $isVendor))
                <a href="{{ route('profile.dispute.escalate' , $purchase)}}" class="btn btn-outline-danger">Escalate Dispute</a>
            @elseif($purchase->dispute->isEscalated())
                <a class="disabled btn btn-primary">Escalated by {{ $purchase->dispute->getEscalatedBy() }} {{ $purchase->dispute->isAppealing() ? ' and its been Appealing' : ''}}</a>
           @elseif($purchase->dispute->isAppealing())
                <a class="disabled btn btn-primary">Appealing by {{ $purchase->dispute->getAppealedBy() }}</a>
            @endif
        </h3>
        <hr>
       @if($isBuyer && !$purchase -> dispute -> isResolved())
        <a href="{{ route('profile.purchases.disputes.close.confirm' , $purchase)}}" class="btn btn-outline-danger">Close Dispute</a>
       @endif

        @if($purchase->dispute->isAppealing())
            <p class="alert alert-warning">Appealing in progress after Dispute was resolved</p>

            @if($isModeratorDispute || $isAdmin)
                   <h5 class="mb-1">Dispute winner before Appeal</h5>
                   <p class="alert alert-success"><strong>{{ $purchase -> dispute -> winner -> username }}</strong></p>
            @endif

        @elseif($purchase -> dispute -> isResolved())
            <h5 class="mb-1">Dispute resolved</h5>
            <p class="alert alert-success">Winner @if (!$purchase->dispute->isAllowedToAppeal()) (Final decision): @endif
                 <strong>{{ $purchase -> dispute -> winner -> username }}</strong> </p>
        @endif


        @if($purchase->isBuyer() && $purchase -> dispute -> isResolved() && $purchase->dispute->isAllowedToAppeal() && $purchase->dispute->resolved_by_user_id != $purchase->buyer->id)
            <a href="{{ route('profile.purchase.dispute.appeal' , $purchase)}}" class="btn btn-outline-danger">Appeal Dispute</a>
        @endif

        @if( (!$purchase -> dispute -> isResolved() && $isModeratorDispute)  ||  ($purchase->dispute->isAppealing() && $isModeratorAppeal) || auth() -> user() -> isAdmin()  )


            @if(!$purchase->dispute->isAppealing())
                <h5 class="mb-1">Resolve dispute</h5>
            @else
                <h5 class="mb-1">Resolve Appeal</h5>
            @endif


            <form action="{{ !$purchase->dispute->isAppealing() ? route('profile.purchases.disputes.resolve', $purchase) : route('profile.purchases.appeal.resolve', $purchase) }}" class="form-inline"
                  method="POST">

                {{ csrf_field() }}

                <div class="form-outline mb-4">
                    <label for="winner" class="mr-2">Select winner:</label>
                </div>
                <div class="form-outline mb-4">
                     <select name="winner" id="winner" class="form-control mr-2">
                         <option selected disabled>Select Winner</option>
                         <option value="{{ $purchase -> vendor -> id }}">Seller Wins the Dispute <strong>{{ $purchase -> vendor -> user -> username }}</strong> - vendor</option>
                         <option value="{{ $purchase -> buyer -> id }}">Buyer Wins the Dispute <strong>{{ $purchase -> buyer -> username }}</strong> - buyer</option>
                    </select>
                </div>

                <hr/>
                <button type="submit" class="btn btn-outline-primary" style="width: auto"> Resolve  @if(!$purchase->dispute->isAppealing()) dispute @else Appeal @endif </button>
            </form>

        @endif


        @if($purchase -> dispute -> messages)

            @foreach($purchase -> dispute -> messages as $message)
                <div class="card my-2">
                    <div class="card-body">
                        {{ $message -> message }}
                    </div>
                    <div class="card-footer text-muted">
                        {{ $message -> time_ago }} by

                   @if($message->author_id == $purchase->vendor->id )
                        <a href="{{ route('vendor.show', $message -> author) }}">
                   @endif

                            {{ $message -> author -> username }}


                            @if ($message->author_id == $purchase->vendor->id)
                                (vendor)
                            @elseif($message->author_id == $purchase->buyer->id)
                                (buyer)
                            @elseif($isAdmin)
                                (admin)
{{--                            @elseif(auth()->user()->hasPermission('disputes') || auth()->user()->hasPermission('disputeappeals'))--}}
{{--                                (moderator)--}}
                            @else
                                (moderator)
                            @endif


                   @if($message->author_id == $purchase->vendor->id )
                        </a>
                    @endif

                    </div>
                </div>
            @endforeach

        @endif

{{--        && !$notBuyerOrVendor && !$purchase->dispute->isEscalated() && !$purchase->dispute->isAppealing()--}}

    @if(( !$purchase->dispute->isResolved() && !$notBuyerOrVendor  )|| (auth() -> user() -> isAdmin() || auth()->user()->hasPermission('disputes') || auth()->user()->hasPermission('disputeappeals')))

            <form action="{{ (!$notBuyerOrVendor) ? route('profile.purchases.disputes.message', $purchase -> dispute) :  route('admin.purchase.disputes.message', $purchase -> dispute)}}" method="POST">

                {{ csrf_field() }}

                   <?php if($notBuyerOrVendor): ?>
                        <input type="hidden" name="dispute_id" value="{{$purchase->dispute_id}}" />
                   <?php endif; ?>

                <div class="card my-2">
                    <div class="card-header">
                        <h5><label for="newmessage">New message:</label></h5>
                    </div>
                    <div class="card-body">
                                <textarea name="message" id="newmessage" class="form-control" id="message" rows="5"></textarea>
                    </div>
                    <div class="card-footer">
                        <button  type="submit" class="btn btn-block btn-primary">Send message</button>
                    </div>
                </div>

            </form>

        @endif



  @endif


    @php
        $showDispute = ($purchase->state == 'purchased' || $purchase->state == 'sent' );
    @endphp

    @if(!$isDisputed && $isBuyer && $showDispute)
        <h3 class="mb-1">Initiate Dispute</h3>
        <hr>
        <p class="text-muted">If the described item does not match received item you can initiate dispute against seller. Once dispute is started, it can be resolved in favor of both buyer and vendor</p>
        <form method="POST" action="{{ route('profile.purchases.dispute', $purchase) }}">
            {{ csrf_field() }}
            <label for="message">Dispute message:</label>
            <textarea name="message" id="message" class="form-control" rows="5" placeholder="Type the message for the dispute"></textarea>
            <button type="submit" class="btn btn-block mt-2  btn-danger">Submit dispute</button>
        </form>
  @endif


</div>

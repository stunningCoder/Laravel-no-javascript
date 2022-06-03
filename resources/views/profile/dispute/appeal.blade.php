<?php
	/**
	 * @var App\Purchase $purchase
     *
	 */
?>
@extends('master.confirmationWithMessage')

@section('confirmation-title', 'Appeal Dispute for purchase' . $purchase->id)

@section('confirmation-content')


    <div class="modal fade in show position-static d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">



@if($purchase->dispute->isAllowedToAppeal() && $purchase->dispute->state != \App\Dispute::APPEAL_STATE)
                    <div class="modal-header">
                        <h5 class="modal-title">Appeal Dispute for purchase "{{ $purchase->id }}"</h5>
                        <a href="{{ route('profile.purchases.single', $purchase ) }}" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>

                <form action="{{ route('profile.purchase.dispute.appeal.confirm', $purchase) }}" method="POST" class="my-2">
                    <div class="modal-body">
                        {{ csrf_field() }}

                        <p>This action can't be undone! Confirm that you want to Appeal <strong>{{ $purchase -> offer -> product -> name }}</strong> in quantity of <em>{{ $purchase -> quantity }}</em>
                            <br>Purchase ID: {{ $purchase->id }}</p>

                    </div>

                    <div class="modal-footer text-center justify-content-center">
                        <a href="{{ route('profile.purchases.single', $purchase ) }}" class="btn btn-secondary" style="margin-left: auto;">Dismiss</a>
                        <button type="submit" class="btn btn-success" style="width: auto;">Confirm</button>
                    </div>

                </form>
@else
                    <div class="modal-header">
                        <h5 class="modal-title">Dispute is already Appealed for purchase "{{ $purchase->id }}"</h5>
                        <a href="{{ route('profile.purchases.single', $purchase ) }}" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
@endif


            </div>
        </div>
    </div>




@endsection


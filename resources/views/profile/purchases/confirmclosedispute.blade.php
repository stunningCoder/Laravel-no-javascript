@extends('master.confirmation')



@section('confirmation-title', 'Confirm Close dispute for purchase - ' . $purchase-> short_id)

@section('confirmation-content')
    This action can't be undone! Confirm that you want to close dispute for <strong>{{ $purchase -> offer -> product -> name }}</strong> in quantity of <em>{{ $purchase -> quantity }}</em>
    <br>
    Purchase ID: {{ $purchase -> short_id }}
@endsection

@section('confirmation-back', route('profile.purchases.single', $purchase))
@section('confirmation-next', route('profile.purchases.disputes.close', $purchase))

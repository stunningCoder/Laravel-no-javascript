@extends('master.admin')

@section('admin-content')


    <h1 class="mb-3"> {{ \App\Dispute::$states[$state]  }}</h1>

    <ul class="nav nav-tabs nav-fill mb-3">

@foreach( \App\Dispute::$states AS $k => $s)

    @if($k == 'appeals' && !auth()->user()->hasPermission('disputeappeals') && !auth()->user()->isAdmin())
        @php
            continue;
        @endphp
    @endif

        <li class="nav-item">
            <a class="nav-link @if($state == $k) active font-weight-bolder @endif" href="{{ route('admin.disputes' , $k) }}">
                {{ $s  }}
                ({{ \App\Dispute::where('state', $k)->count()  }})
            </a>
        </li>

@endforeach


    </ul>

    <table class="table">
        <thead>
        <tr>
            <th>Purchase</th>
            <th>Buyer</th>
            <th>Vendor</th>
            <th>Winner</th>
            <th>Total</th>
            <th>Status</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($allDisputes as $dispute)
            <tr>
                <td>
                    <a href="{{ route('admin.purchase', $dispute -> purchase) }}" class="btn btn-sm btn-mblue mt-1">{{ $dispute -> purchase -> short_id }}</a>
                </td>
                <td>


                    @if(auth()->user()->isAdmin())
                        <a href="{{route('admin.users.view', $dispute -> purchase -> buyer)}}">{{ $dispute -> purchase -> buyer -> username }}</a>
                    @else
                        {{ $dispute -> purchase -> buyer -> username }}
                    @endif

                </td>
                <td>
                    <a href="{{ route('vendor.show', $dispute -> purchase -> vendor) }}">{{ $dispute -> purchase -> vendor -> user -> username }}</a>
                </td>
                <td>
                    @if($dispute -> isResolved())
                        {{ $dispute -> winner -> username }}
                    @else

                        @if($dispute->state == 'open')
                            <span class="badge badge-warning">Unresolved</span>
                        @elseif($dispute->state == 'escalated')
                            <span class="badge badge-warning">Awaiting Action</span>
                        @elseif($dispute->state == 'appeals')
                            <span class="badge badge-warning">Appeal by </span>
                        @endif

                    @endif


                </td>
                <td>
                    {{$dispute->purchase->getSumLocalCurrency()}} {{$dispute -> purchase->getLocalSymbol()}}
                    {{--{{ $dispute -> purchase -> value_sum }} $--}}
                </td>
                <td>
                    @if ($dispute->lastReplyFrom() != $dispute -> purchase -> buyer -> username && $dispute->lastReplyFrom() != $dispute -> purchase -> vendor -> user -> username)
                        {{ 'Awaiting information' }}
                    @else
                        Reply from {{ ($dispute->lastReplyFrom() == $dispute -> purchase -> buyer -> username )? 'Buyer' : 'Vendor' }}
                    @endif

                {{ $dispute->lastReplyFrom()  }}
                </td>
                <td>
                    {{ $dispute -> timeDiff() }}
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="text-center">
                {{ $allDisputes->links('includes.paginate') }}
            </div>
        </div>
    </div>


@stop

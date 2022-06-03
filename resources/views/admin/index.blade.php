@extends('master.admin')

@section('admin-content')


    @if(!auth() -> user() -> isAdmin())
        <h2 class="mb-3">Permissions</h2>

        <ul>
            @foreach(auth() -> user()->permissions AS $permission)
                <li>{{ \App\User::$permissionsLong [ $permission->name ] }}</li>
            @endforeach
        </ul>

    @endif

    <h2 class="mb-3">Market Statistics</h2>

    <div class="card-columns">

        @hasAccess('products')
            <div class="card text-center">
                <div class="card-body">
                    <h1>
                        {{ $total_products }}
                    </h1>
                </div>
                <div class="card-footer">
                    Number of products in market
                </div>
            </div>
        @endhasAccess

        @hasAccess('purchases')
            <div class="card text-center">
                <div class="card-body">
                    <h1>
                        {{ $total_purchases }}
                    </h1>
                </div>
                <div class="card-footer">
                    Times someone bought a products from market
                </div>
            </div>
        @endhasAccess

        @hasAccess('disputeappeals')
            <div class="card text-center">
                <div class="card-body">
                    <h1>Dispute Appeals</h1>
                </div>
                <div class="card-footer">
                    <ul class="nav nav-tabs nav-fill mb-3">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.disputes' , 'open') }}">
                                    Open ({{ \App\Appeals::where('resolver_id', null)->count()  }})
                                </a>
                            </li>


                      @if(!auth()->user()->isAdmin())
                            <li class="nav-item"><a>Solved ({{ \App\Appeals::where('resolver_id', auth()->id())->count()  }})</a></li>
                      @else
                            <li class="nav-item"><a>Solved ({{ \App\Appeals::whereNotNull('resolver_id')->count() }} )</a></li>
                      @endif

                    </ul>
                </div>
            </div>
        @endhasAccess

        @hasAccess('disputes')
            <div class="card text-center">
                <div class="card-body">
                    <h1>Disputes</h1>
                </div>
                <div class="card-footer">
                    <ul class="nav nav-tabs nav-fill mb-3">

                        @foreach( \App\Dispute::$states AS $k => $s)

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.disputes' , $k) }}">
                                    {{ $s }} ({{ \App\Dispute::where('state', $k)->count()  }})
                                </a>
                            </li>

                        @endforeach


                    </ul>
                </div>
            </div>
        @endhasAccess

        @hasAccess('tickets')
            <div class="card text-center">
                <div class="card-body">
                    <h1>Tickets</h1>
                </div>
                <div class="card-footer">
                    <ul class="nav nav-tabs nav-fill mb-3">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.tickets') }}">
                            Open ({{ \App\Ticket::where('answered', 0)->count()  }})
                            </a>
                        </li>


                        @if(!auth()->user()->isAdmin())
                            <li class="nav-item"><a>Solved ({{ \App\Ticket::where('solved', 0)->count()  }})</a></li>
                        @else
                            <li class="nav-item"><a>Solved ({{ \App\Ticket::where('solved', 0)->count() }} )</a></li>
                        @endif


                    </ul>
                </div>
            </div>
        @endhasAccess

        @hasAccess('users')
            <div class="card text-center">
                <div class="card-body">
                    <h1>
                        {{ $total_users }}
                    </h1>
                </div>
                <div class="card-footer">
                    Number of users registered in market
                </div>
            </div>


            <div class="card text-center">
                <div class="card-body">
                    <h1>
                        {{ $total_vendors }}
                    </h1>
                </div>
                <div class="card-footer">
                    Number of vendors on this market
                </div>
            </div>
        @endhasAccess

        @if(auth() -> user() -> isAdmin())
                <div class="card text-center">
                    <div class="card-body">
                        <h1>
                            @include('includes.currency', ['usdValue' => $avg_product_price])
                        </h1>
                    </div>
                    <div class="card-footer">
                        Average product price
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h1>
                            @include('includes.currency', ['usdValue' => $total_spent])
                        </h1>
                    </div>
                    <div class="card-footer">
                        Total money spent on this market
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h1>
                            {{ $total_daily_purchases }}
                        </h1>
                    </div>
                    <div class="card-footer">
                        Purchases in last 24h
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <table class="table table-borderless">
                            @foreach($total_earnings_coin as $coin => $total_sum)
                                <tr>
                                    <td><span class="badge badge-primary">{{ strtoupper(\App\Purchase::coinDisplayName($coin)) }}</span></td>
                                    <td class="text-right">{{ number_format(round($total_sum, 8), 8) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="card-footer">
                        Total earnings per coin
                    </div>
                </div>

            </div>
        @endif


@stop

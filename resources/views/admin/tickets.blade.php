@extends('master.admin')

@section('admin-content')

    <h1 class="mb-3"> {{ $state }} Tickets</h1>

    <ul class="nav nav-tabs nav-fill mb-3">

            <li class="nav-item">
                <a class="nav-link @if($state == 'Open') active font-weight-bolder @endif" href="{{ route('admin.tickets' , 'Open') }}">
                    Open Tickets
                    ({{ \App\Ticket::where([ ['answered', 0],['solved', 0] ])->count()  }})
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if($state == 'Answered') active font-weight-bolder @endif" href="{{ route('admin.tickets' , 'Answered') }}">
                    Answered Tickets
                    ({{ \App\Ticket::where([ ['answered', 1],['solved', 0] ])->count()  }})
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if($state == 'Solved') active font-weight-bolder @endif" href="{{ route('admin.tickets' , 'Solved') }}">
                    Solved Tickets
                    ({{ \App\Ticket::where('solved', 1)->count()  }})
                </a>
            </li>

  @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('deletetickets'))
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.remove') }}">Removed Tickets</a></li>
  @endif

    </ul>

    <table class="table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Opened by</th>

          @if($state == 'Solved')
            <th>Solved by</th>
          @endif

            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>
                    <a href="{{ route('admin.tickets.view', $ticket) }}" class="mt-1">{{ $ticket -> title }}</a>
                    @if($ticket -> solved)
                    <span class="badge badge-success">Solved</span>
                    @else
                        @if($ticket -> answered)
                            <span class="badge badge-warning">Answered</span>
                        @endif
                    @endif
                </td>
                <td>
                   @if($ticket->user->username)

                       @if(auth()->user()->isAdmin())
                                <a href="{{route('admin.users.view', $ticket->user)}}">{{ $ticket->user->username }}</a>
                       @else
                           {{ $ticket->user->username }}
                       @endif

                    @endif
                </td>

                @if($state == 'Solved')
                    <td>

                        @if(auth()->user()->isAdmin())
                            <a href="{{route('admin.users.view', $ticket -> resolvedBy)}}">{{ $ticket -> resolvedBy -> username }}</a>
                        @else
                            {{ $ticket -> resolvedBy -> username }}
                        @endif

                    </td>
                @endif

                <td>
                    <small>{{ $ticket -> time_passed }}</small>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="text-center">
                {{ $tickets->links('includes.paginate') }}
            </div>
        </div>
    </div>



@stop

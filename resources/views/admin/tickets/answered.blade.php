@extends('master.admin')

@section('admin-content')
    <div class="row mb-4">
        <div class="col">
            <h3>
                Answered Tickets
            </h3>
        </div>
    </div>

    <div class="row mt-1 mb-3">
        <div class="offset-md-9 col-md-3">
            <a href="{{ route('admin.tickets.remove') }}" class="btn btn-outline-info mt-1">Remove Tickets</a>
        </div>
    </div>

    <div class="row mt-1 mb-3">
        <div class="col">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.tickets.open') }}" class="btn btn-outline-info mt-1 mr-2">Open tickets</a>
                <a href="{{ route('admin.tickets.answered') }}" class="btn btn-primary mt-1 mr-2">Answered tickets</a>
                <a href="{{ route('admin.tickets.solved') }}" class="btn btn-outline-info mt-1 mr-2">Solved tickets</a>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Opened by</th>
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
                    <strong>{{ $ticket -> user -> username }}</strong>
                </td>
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

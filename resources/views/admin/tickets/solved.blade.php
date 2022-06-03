@extends('master.admin')

@section('admin-content')
    <div class="row mb-4">
        <div class="col">
            <h3>
                Solved Tickets
            </h3>
        </div>
    </div>


    <div class="row mt-1 mb-3">
        <div class="col">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.tickets.open') }}" class="btn btn-outline-info mt-1 mr-2">Open tickets</a>
                <a href="{{ route('admin.tickets.answered') }}" class="btn btn-outline-info mt-1 mr-2">Answered tickets</a>
                <a href="{{ route('admin.tickets.solved') }}" class="btn btn-primary mt-1 mr-2">Solved tickets</a>
            </div>
        </div>
    </div>

    <div class="row mt-1 mb-3">
        <div class="col">
            <form action="{{route('admin.tickets.remove')}}" method="post">
                {{csrf_field()}}

                <div class="row text-right" role="group">
                    <div class="offset-md-7 btn-group" role="group">
                        <button type="submit" class="btn btn-outline-info mr-2" name="type" value="solved">Remove solved tickets</button>
                        <button type="submit" class="btn btn-outline-info" name="type" value="all">Remove all tickets</button>
                    </div>
                </div>


                <div class="row mt-2" role="group">
                <div class="input-group col-md-4 mb-3 mt-2">
                    <input type="text" class="form-control" placeholder="Older than (Days)" name="days" aria-label="Days" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-info" type="submit" name="type" value="orlder_than_days">Remove all</button>
                    </div>
                </div>
                </div>

            </form>
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

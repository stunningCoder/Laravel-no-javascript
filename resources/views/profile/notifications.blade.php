@extends('master.profile')

@section('title', 'Notifications')

@section('profile-content')
    @include('includes.flash.success')
    @include('includes.flash.error')

    <h1 class="mb-3">Notifications.</h1>
    <hr>
    <p>Here you will find your ManCave Market Notifications, feel free to delete them at any time!</p>
     <br>
    <form action="{{route('profile.notifications.delete')}}" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash"></i> Delete All Alerts
            </button>
        </div>
    </form>

    <br>

    <table class="table table-hover">
        <thead>
        <th>Notification</th>
        <th>Time</th>
        <th>Action</th>
        </thead>
        @foreach($notifications as $notification)
            <tr>
                <td>
                    {{$notification->description}}
                </td>
                <td>
                    {{$notification->created_at->diffForHumans()}}
                </td>
                <td>
                    @if($notification->getRoute() !== null )
                        <a href="{{route($notification->getRoute(),$notification->getRouteParams())}}" class="btn btn-outline-secondary"><i class="fa fa-eye"></i> View</a>
                    @else
                        None
                    @endif
                </td>
            </tr>

        @endforeach
    </table>
    <div class="mt-3">
        {{$notifications->links()}}
    </div>

@stop

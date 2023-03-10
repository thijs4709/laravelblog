@extends('layouts.admin')
@section('title')
    Users
@endsection
@section('content')
    <h1>USERS</h1>
    @if(session('status'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
            <strong>Success!</strong>{{session('status')}}
        </div>
    @endif
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Id</th>
            <th>Photo</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Roles</th>
            <th>Active</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Deleted</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            {{--                @if($loop->first)  de loop kan je alleen gebruiken in een foreach--}}
            {{--                    aantal : {{$loop->count}}--}}
            {{--                @endif--}}

            <tr>
                <td>{{$user->id}}</td>

                <td>
                    <a href="{{route('users.edit',$user->id)}}">
                        <img class="img-thumbnail" width="62" height="62"
                             src="{{$user->photo ? asset($user->photo->file) : 'http://via.placeholder.com/62x62'}}"
                             alt="{{$user->name}}">
                    </a>
                </td>
                <td><a href="{{route('users.edit',$user->id)}}">{{$user->name}}</a></td>
                <td>{{$user->email}}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge badge-pill badge-info">
                                {{$role->name}}
                            </span>
                    @endforeach
                </td>
                <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not Active'}}</td>
                <td>{{$user->created_at}}</td>
                <td>{{$user->updated_at}}</td>
                <td>{{$user->deleted_at}}</td>
                <td>
                    @if($user->deleted_at != null)
                        <a class="btn btn-warning" href="{{route('admin.userrestore', $user->id)}}">Restore</a>
                    @else
                        {!! Form::open(['method'=>'DELETE', 'action'=>['\App\Http\Controllers\AdminUsersController@destroy',$user->id]]) !!}
                        <div class="form-group">
                            {!! Form::submit('Delete User',['class'=>'btn btn-danger']) !!}
                        </div>
                        {!! Form::close() !!}
                    @endif
                </td>

            </tr>

        @endforeach
        {{--                @foreach($users as $user)--}}
        {{--                    <tr>--}}
        {{--                        <td>{{$user->user_id}}</td>--}}
        {{--                        <td>{{$user->photo_id}}</td>--}}
        {{--                        <td>{{$user->user_name}}</td>--}}
        {{--                        <td>{{$user->email}}</td>--}}
        {{--                        --}}{{--                    <td>{{$user->role_id?$user->role->name:'User without role'}}</td>--}}
        {{--                        @dd($user);--}}
        {{--                        <td>--}}
        {{--                            @foreach($user->role_names as $role)--}}
        {{--                                <span class="badge badge-pill badge-info">--}}
        {{--                                    {{$role}}--}}
        {{--                                </span>--}}
        {{--                            @endforeach--}}
        {{--                        </td>--}}
        {{--                        <td class="{{$user->is_active == 1?'bg-success':'bg-danger'}}">{{$user->is_active == 1?'Active':'Not Active'}}</td>--}}
        {{--                        <td>{{$user->user_created_at}}</td>--}}
        {{--                        <td>{{$user->user_updated_at}}</td>--}}
        {{--                    </tr>--}}
        {{--                @endforeach--}}
        </tbody>
    </table>
    {{$users->links()}}
@endsection

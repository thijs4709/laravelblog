@extends('layouts.admin')
@section('title')
    Comments
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="d-flex col-lg-4 p-0">
                <div class="card h-100 shadow-lg mb-5 bg-body-tertiary rounded">
                    <div class="card-header">
                        <h3><strong>Comment {{$comment->id}}</strong></h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Comment Body:</strong>{{$comment->body}}</p>
                    </div>
                    <div class="card-footer">
                        <p class="m-0">
                            <strong>Author: {{$comment->user->name}}</strong>
                        </p>
                    </div>
                </div>
                <div class="m-2 my-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-arrow-right-circle text-primary" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                    </svg>
                </div>
            </div>
            <div class="d-flex col-lg-4 p-0">
                @if($comment->parent_id)
                    <div class="card h-100 shadow-lg mb-5 bg-body-tertiary rounded">
                        <div class="card-header">
                            <h3><strong>Parent Comment {{$comment->parent_id}}</strong></h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Comment Body:</strong>{{$comment->parent->body}}</p>
                        </div>
                        <div class="card-footer">
                            <p class="m-0 d-flex justify-content-between align-items-center">
                                <strong>Author: {{$comment->parent->user->name}}</strong>
                                <a class="btn btn-primary rounded-pill" href="{{route('comments.show', $comment->parent_id)}}">GO</a>
                            </p>
                        </div>
                    </div>
                    <div class="m-2 my-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-arrow-right-circle text-primary" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                        </svg>
                    </div>
                @else
                    <div class="card h-100 w-100 d-flex justify-content-center align-items-center">
                        <p>No Parent Comment</p>
                    </div>
                    <div class="m-2 my-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-arrow-right-circle text-primary" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="d-flex col-lg-4 p-0">
                <div class="card h-100 shadow-lg mb-5 bg-body-tertiary rounded">
                    <div class="card-header">
                        <h3><strong>Post {{$comment->post_id}} : <small>{{$comment->post->title}}</small></strong></h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Comment Body:</strong>{{$comment->post->body}}</p>
                    </div>
                    <div class="card-footer">
                        <p class="m-0">
                            <strong>Author: {{$comment->post->user->name}}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-5 p-0 shadow-lg mb-5 bg-body-tertiary rounded">
                @if($comment->children->count() > 0)
                    <div class="rounded p-3">
                        <h3>Child Comments</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Body</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($comment->children as $child)
                                <tr>
                                    <td><a href="{{route('comments.show',$child->id)}}">{{$child->id}}</a></td>
                                    <td>{{$child->user->name}}</td>
                                    <td>{{$child->body}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No Child Commetns Found!</p>
                @endif
                    <a class="btn btn-primary" href="{{route('comments.index')}}">All Comments</a>
            </div>
        </div>
    </div>
@endsection

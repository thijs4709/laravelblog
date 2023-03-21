<li class="single_comment_area">
    <div class="comment-wrapper d-md-flex align-items-start">
        <!-- Comment Meta -->
        <div class="comment-author">
            <img src="{{asset('img/imagesfront/blog-img/25.jpg')}}" alt="">
        </div>
        <!-- Comment Content -->
        <div class="comment-content">
            <h5>{{$comment->user->name}}</h5>
            <span class="comment-date font-pt">{{$comment->created_at->format('F d, Y')}}</span>
            <p>{{$comment->body}}</p>
            <a class="reply-btn" href="#">Reply <i class="fa fa-reply" aria-hidden="true"></i></a>
        </div>
    </div>
    @if($comment->children->isNotEmpty())
        <ol class="children pl-5">
            @foreach($comment->children as $child)
                @include('components._comment',['comment'=>$child])
            @endforeach
        </ol>
    @endif
</li>

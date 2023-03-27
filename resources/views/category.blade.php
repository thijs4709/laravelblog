@extends('layouts.frontend')

@section('content')
<!-- Breadcumb Area Start -->
<div class="breadcumb-area section_padding_50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breacumb-content d-flex align-items-center justify-content-between">
                    <!-- Post Tag -->
                    <div class="gazette-post-tag">
                        <a href="#">{{$category->name}}</a>
                    </div>
                    <p class="editorial-post-date text-dark mb-0">{{date('F j, Y')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcumb Area End -->

<!-- Editorial Area Start -->
<section class="gazatte-editorial-area section_padding_100 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    @foreach($sliderPosts as $sliderPost)
                        <!-- Editorial Post Single Slide -->
                        <div class="editorial-post-single-slide">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <div class="editorial-post-thumb">
                                        <img src="{{$sliderPost->photo ? $sliderPost->photo->file : ''}}" alt="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div class="editorial-post-content">
                                        <!-- Post Tag -->

                                        <div class="gazette-post-tag">
                                            @foreach($sliderPost->categories as $category)
                                                <a href="#">
                                                    {{$category->name}}
                                                </a>
                                            @endforeach

                                        </div>
                                        <h2><a href="#" class="font-pt mb-15">{{$sliderPost->title}}</a></h2>
                                        <p class="editorial-post-date mb-15"{{$sliderPost->created_at ? $sliderPost->created_at->diffForHumans() : date('F j, Y')}}</p>
                                        <p>{{Str::limit($sliderPost->body, 300)}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Editorial Area End -->

<section class="catagory-welcome-post-area section_padding_100">
    <div class="container">
        <div class="row">
            @foreach($posts as $post)
                <div class="col-12 col-md-4">
                    <!-- Gazette Welcome Post -->
                    <div class="gazette-welcome-post">
                        <!-- Post Tag -->
                        <div class="gazette-post-tag">
                            @foreach($post->categories as $mycategory)
                            <a href="{{route('category.category',$mycategory->slug)}}">{{$mycategory->name}}</a>
                            @endforeach
                        </div>
                        <h2 class="font-pt">{{$post->title}}</h2>
                        <p class="gazette-post-date">{{$post->created_at ? $post->created_at->diffForHumans() : date('F j, y')}}</p>
                        <!-- Post Thumbnail -->
                        <div class="blog-post-thumbnail my-5">
                            <img class="img-thumbnail" src="{{$post->photo ? $post->photo->file : ""}}" alt="post-thumb">
                        </div>
                        <!-- Post Excerpt -->
                        <p>{{Str::Limit($post->body,200)}}</p>
                        <!-- Reading More -->
                        <div class="post-continue-reading-share mt-30">
                            <div class="post-continue-btn">
                                <a href="{{route('frontend.post', $post->slug)}}" class="font-pt">Continue Reading <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-5">
                {{$posts->links()}}
            </div>
        </div>
    </div>
</section>
@endsection

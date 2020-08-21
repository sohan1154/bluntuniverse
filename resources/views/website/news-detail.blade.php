@extends('layouts.website')

@section('content')

<div class="col-lg-9 posts-list" style="text-align: justify;">
    <div class="single-post">
        <div class="feature-img">
            <img class="img-fluid" src="{{ url('/') }}/uploads/news/{{ $newsDetail->id }}/{{ $newsDetail->image }}" alt="">
        </div>
        <div class="blog_details">
            <h1 class="blog-heading">{{ $newsDetail->title }}</h1>
            {!! $newsDetail->description !!}
        </div>
    </div>
</div>

@stop
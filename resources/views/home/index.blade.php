@extends('layouts.main')
@section('content')
    <!-- Main Content -->
    <div class="main-content" style="min-height: 517px;">
        <section class="section">
            <div class="section-header">
                <h1>{{$heading}}</h1>
                {{--<div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Components</a></div>
                    <div class="breadcrumb-item">Article</div>
                </div>--}}
            </div>

            <div class="section-body">
                <h2 class="section-title">{{$heading}}</h2>
                <div class="row">
                    @forelse($photos as $photo)
                    <div class="col-12 col-md-4 col-lg-4">
                        <article class="article article-style-c">
                            <div class="article-header">
                                <div class="article-image">
                                    <a href="{{route('photos.show',[$photo->slug])}}">
                                        <img width="350" height="233" src="{{$photo->thumbnail_url}}" alt="{{$photo->title}}">
                                    </a>
                                </div>
                            </div>
                            <div class="article-details">
                                <div class="article-category"><div class="bullet"></div> <a href="#">{{$photo->created_at->diffForHumans()}}</a></div>
                                <div class="article-title">
                                    <h2><a href="{{route('photos.show',[$photo->slug])}}">{{$photo->title}}</a></h2>
                                </div>
                                <div class="article-user">
                                    <a href="">
                                        <img alt="{{$photo->album->user->name}} avatar" src="{{asset('assets/img/avatar/avatar-1.png')}}">
                                    </a>
                                    <div class="article-user-details">
                                        <div class="user-detail-name">
                                            <a href="#">{{$photo->album->user->name}}</a>
                                        </div>
                                        <div class="text-job">
                                            <a href="">{{$photo->album->title}}</a>
                                            {{$photo->album->photos->count()}} {{Str::plural('photo', $photo->album->photos->count())}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    @empty
                       <h4>aucune image pour le moment</h4>
                    @endforelse
                </div>
            </div>
        </section>

        <nav>
            {!!$photos->links()!!}
        </nav>
    </div>
@stop

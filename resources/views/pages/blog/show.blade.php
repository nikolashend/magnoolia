@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('meta_description', Str::limit(strip_tags($post->content), 160))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => $post->title,
    'breadcrumbs' => [
        __('blog.page_title') => route('blog.index'),
        $post->title => route('blog.show', $post->slug),
    ],
])

<section class="blog-details section-space">
    <div class="container">
        <div class="row gutter-y-40">

            <div class="col-lg-8">
                <div class="blog-details__content">
                    <div class="blog-details__image hover:shine">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                    </div>
                    <div class="blog-details__meta mt-20">
                        <span><i class="fas fa-user-circle"></i> {{ $post->author->name }}</span>
                        <span><i class="icon-celemder"></i> {{ $post->created_at->format('d M Y') }}</span>
                    </div>
                    <h2 class="blog-details__title mt-20">{{ $post->title }}</h2>
                    <div class="blog-details__text mt-20">
                        {!! $post->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->count())
                    <div class="blog-details__tags mt-30">
                        <span>{{ __('blog.tags') }}:</span>
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="blog-tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count())
                <div class="blog-details__related mt-60">
                    <h3>{{ __('blog.related_posts') }}</h3>
                    <div class="row gutter-y-30 mt-30">
                        @foreach($relatedPosts as $related)
                        <div class="col-md-6">
                            <div class="blog-card blog-card--small">
                                <div class="blog-card__image hover:shine">
                                    <a href="{{ route('blog.show', $related->slug) }}">
                                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}">
                                    </a>
                                </div>
                                <div class="blog-card__content">
                                    <h3 class="blog-card__title">
                                        <a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <div class="blog-sidebar__widget">
                        <h4 class="blog-sidebar__title">{{ __('blog.recent_posts') }}</h4>
                        @foreach($recentPosts ?? [] as $recent)
                        <div class="blog-sidebar__post">
                            <a href="{{ route('blog.show', $recent->slug) }}" class="blog-sidebar__post__image">
                                <img src="{{ $recent->thumbnail_url }}" alt="{{ $recent->title }}">
                            </a>
                            <div class="blog-sidebar__post__content">
                                <span>{{ $recent->created_at->format('d M Y') }}</span>
                                <h5><a href="{{ route('blog.show', $recent->slug) }}">{{ $recent->title }}</a></h5>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

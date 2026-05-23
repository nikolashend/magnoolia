@extends('layouts.app')

@section('title', __('blog.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('blog.page_title'),
    'breadcrumbs' => [__('blog.page_title') => route('blog.index')],
])

<section class="blog-page section-space">
    <div class="container">
        <div class="row gutter-y-30">
            @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1500ms">
                <div class="blog-card">
                    <div class="blog-card__image hover:shine">
                        <a href="{{ route('blog.show', $post->slug) }}">
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                        </a>
                        <div class="blog-card__date">
                            <span>{{ $post->created_at->format('d') }}</span>
                            {{ $post->created_at->format('M') }}
                        </div>
                    </div>
                    <div class="blog-card__content">
                        <ul class="blog-card__meta list-unstyled">
                            <li><i class="fas fa-user-circle"></i> {{ $post->author->name }}</li>
                            <li><i class="icon-celemder"></i> {{ $post->created_at->format('d M Y') }}</li>
                        </ul>
                        <h3 class="blog-card__title">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="blog-card__text">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="zoomvilla-btn blog-card__btn">
                            {{ __('common.read_more') }} <i class="icon-angle-small-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>{{ __('blog.no_posts') }}</p>
            </div>
            @endforelse
        </div>

        <div class="mt-40 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', __('common.search_results'))

@section('content')
    @include('partials.page-header', ['title' => __('common.search_results'), 'breadcrumbs' => [
        ['label' => __('menu.home'), 'url' => route('home')],
        ['label' => __('common.search')],
    ]])

    <section class="search-page">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <form action="{{ route('search') }}" method="GET" class="mb-5">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" value="{{ $query ?? '' }}" placeholder="{{ __('common.search_placeholder') }}">
                            <button type="submit" class="thm-btn">{{ __('common.search') }}</button>
                        </div>
                    </form>

                    @if(isset($query) && $query)
                        @if(isset($apartments) && $apartments->count())
                            <h4 class="mb-3">{{ __('menu.apartments') }}</h4>
                            @foreach($apartments as $apartment)
                                <div class="search-result-item mb-3">
                                    <h5><a href="{{ route('apartments.show', $apartment->slug) }}">{{ $apartment->title }}</a></h5>
                                    <p>{{ Str::limit($apartment->description, 150) }}</p>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($posts) && $posts->count())
                            <h4 class="mb-3 mt-4">{{ __('menu.blog') }}</h4>
                            @foreach($posts as $post)
                                <div class="search-result-item mb-3">
                                    <h5><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h5>
                                </div>
                            @endforeach
                        @endif

                        @if((!isset($apartments) || !$apartments->count()) && (!isset($posts) || !$posts->count()))
                            <p>{{ __('common.no_results') }}</p>
                        @endif
                    @endif
                </div>
                <div class="col-xl-4">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection

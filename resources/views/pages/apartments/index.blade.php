@extends('layouts.app')

@section('title', __('apartments.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('apartments.page_title'),
    'breadcrumbs' => [__('apartments.page_title') => route('apartments.index')],
])

<section class="apartments-page section-space">
    <div class="container">

        {{-- Filters --}}
        <form method="GET" action="{{ route('apartments.index') }}" class="apartment-filter mb-50">
            <div class="row gutter-y-20">
                <div class="col-md-4">
                    <input type="text" name="location" class="form-control"
                           placeholder="{{ __('apartments.filter_location') }}"
                           value="{{ request('location') }}">
                </div>
                <div class="col-md-3">
                    <select name="rooms" class="selectpicker form-control">
                        <option value="">{{ __('apartments.filter_rooms') }}</option>
                        <option value="1" {{ request('rooms') == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('rooms') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ request('rooms') == 3 ? 'selected' : '' }}>3+</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="selectpicker form-control">
                        <option value="newest">{{ __('apartments.sort_newest') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('apartments.sort_price_asc') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('apartments.sort_price_desc') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="zoomvilla-btn w-100">
                        {{ __('common.search') }} <i class="icon-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <div class="row gutter-y-30">
            @forelse($apartments as $apartment)
            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1500ms">
                <div class="apartment-card">
                    <div class="apartment-card__image hover:shine">
                        <a href="{{ route('apartments.show', $apartment->slug) }}">
                            <img src="{{ $apartment->thumbnail_url }}" alt="{{ $apartment->title }}">
                        </a>
                        <div class="apartment-card__badge">{{ $apartment->status_label }}</div>
                    </div>
                    <div class="apartment-card__content">
                        <div class="apartment-card__price">{{ $apartment->formatted_price }}</div>
                        <h3 class="apartment-card__title">
                            <a href="{{ route('apartments.show', $apartment->slug) }}">{{ $apartment->title }}</a>
                        </h3>
                        <p class="apartment-card__location">
                            <i class="icon-pin"></i> {{ $apartment->address }}
                        </p>
                        <ul class="apartment-card__features list-unstyled">
                            <li><i class="icon-bedroom"></i> {{ $apartment->rooms }} {{ __('apartments.rooms') }}</li>
                            <li><i class="icon-bath"></i> {{ $apartment->bathrooms }}</li>
                            <li><i class="icon-labyrinth"></i> {{ $apartment->area }} m²</li>
                        </ul>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>{{ __('apartments.no_results') }}</p>
            </div>
            @endforelse
        </div>

        <div class="mt-40 d-flex justify-content-center">
            {{ $apartments->withQueryString()->links() }}
        </div>

    </div>
</section>

@endsection

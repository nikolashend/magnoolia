@extends('layouts.app')

@section('title', __('services.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('services.page_title'),
    'breadcrumbs' => [__('services.page_title') => route('services.index')],
])

<section class="services-page section-space">
    <div class="container">
        <div class="row gutter-y-30">
            @forelse($services as $service)
            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1500ms">
                <div class="service-card">
                    <div class="service-card__icon">
                        <i class="{{ $service->icon ?? 'icon-house' }}"></i>
                    </div>
                    <div class="service-card__content">
                        <h3 class="service-card__title">
                            <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                        </h3>
                        <p class="service-card__text">{{ Str::limit($service->description, 120) }}</p>
                        <a href="{{ route('services.show', $service->slug) }}" class="service-card__btn">
                            {{ __('common.read_more') }} <i class="icon-angle-small-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>{{ __('services.no_services') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection

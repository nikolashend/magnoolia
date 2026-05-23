@extends('layouts.app')

@section('title', __('about.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('about.page_title'),
    'breadcrumbs' => [__('about.page_title') => route('about')],
])

<section class="about-one section-space" id="about">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="about-one__image">
                    <div class="about-one__image__item">
                        <div class="about-one__image__item__inner hover:shine">
                            <img src="{{ asset('assets/images/about/about-1-1.jpg') }}" alt="about"
                                 class="about-one__image__one">
                        </div>
                        <div class="about-one__experience wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                            <h3 class="about-one__experience__title">{{ __('about.since') }}</h3>
                        </div>
                    </div>
                    <div class="about-one__image__item-two hover:shine">
                        <div class="about-one__image__icon">
                            <img src="{{ asset('assets/images/shapes/house-1-1.png') }}" alt="icon">
                        </div>
                        <img src="{{ asset('assets/images/about/about-s-1-1.jpg') }}" alt="about"
                             class="about-one__image__one">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="sec-title text-start">
                        <div class="sec-title__top justify-content-start">
                            <span class="line-left"></span>
                            <h6 class="sec-title__tagline bw-split-in-right">{{ __('about.tagline') }}</h6>
                        </div>
                        <h3 class="sec-title__title bw-split-in-left">{{ __('about.title') }}</h3>
                    </div>
                    <p class="about-one__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        {{ __('about.description') }}
                    </p>
                    <div class="about-one__features">
                        <ul class="about-one__features__list list-unstyled">
                            @foreach(__('about.features') as $index => $feature)
                            <li class="wow fadeInUp" data-wow-duration="{{ 1500 + ($index * 100) }}ms">
                                <i class="icon-check-star"></i>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="about-one__shape">
        <img src="{{ asset('assets/images/shapes/about-shape-1-1.png') }}" alt="image">
    </div>
</section>

@endsection

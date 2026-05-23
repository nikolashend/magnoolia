@extends('layouts.app')

@section('title', __('errors.404_title'))
@section('body_class', 'custom-cursor')

@section('content')
<section class="error-page section-space">
    <div class="container">
        <div class="error-page__inner text-center">
            <div class="error-page__number">404</div>
            <h2 class="error-page__title">{{ __('errors.404_heading') }}</h2>
            <p class="error-page__text">{{ __('errors.404_text') }}</p>
            <a href="{{ route('home') }}" class="zoomvilla-btn">
                {{ __('errors.back_home') }} <i class="icon-angle-small-right"></i>
            </a>
        </div>
    </div>
</section>
@endsection

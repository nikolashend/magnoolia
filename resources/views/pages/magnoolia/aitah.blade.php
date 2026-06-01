@extends('layouts.app')

@section('title', __('magnoolia.thankyou.page_title'))
@section('meta_description', __('magnoolia.thankyou.meta_description'))

@push('head')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')

{{-- Hero / Thank-you card --}}
<section class="section-space" style="min-height:60vh;display:flex;align-items:center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 text-center">

                <div style="margin-bottom:24px;">
                    <span style="font-size:56px;line-height:1;">✓</span>
                </div>

                <div class="sec-title text-center" style="margin-bottom:32px;">
                    <div class="sec-title__top justify-content-center">
                        <span class="line-left"></span>
                        <h6 class="sec-title__tagline">{{ __('magnoolia.thankyou.eyebrow') }}</h6>
                        <span class="line-right"></span>
                    </div>
                    <h1 class="sec-title__title" style="font-size:clamp(1.6rem,4vw,2.4rem);">
                        @if($name)
                            {!! __('magnoolia.thankyou.heading_name', ['name' => e($name)]) !!}
                        @else
                            {{ __('magnoolia.thankyou.heading') }}
                        @endif
                    </h1>
                </div>

                <p style="font-size:1.1rem;color:var(--mg-warm-grey);margin-bottom:40px;max-width:520px;margin-left:auto;margin-right:auto;">
                    {{ __('magnoolia.thankyou.body') }}
                </p>

                <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ lroute('home') }}" class="thm-btn">
                        {{ __('magnoolia.thankyou.cta_home') }}
                    </a>
                    <a href="{{ lroute('magnoolia.homes') }}" class="thm-btn thm-btn--dark">
                        {{ __('magnoolia.thankyou.cta_homes') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

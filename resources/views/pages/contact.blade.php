@extends('layouts.app')

@section('title', __('contact.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('contact.page_title'),
    'breadcrumbs' => [__('contact.page_title') => route('contact')],
])

<section class="contact-page contact-four section-space">
    <div class="container">
        <div class="contact-four__inner">
            <div class="row gutter-y-30">

                <div class="col-lg-6">
                    <div class="contact-four__maps">
                        <div class="contact-four__maps__inner">
                            <iframe
                                title="{{ __('contact.map_title') }}"
                                src="{{ config('contact.map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd') }}"
                                allowfullscreen></iframe>
                            <a href="{{ route('home') }}" class="contact-four__maps__logo">
                                <img src="{{ asset('assets/images/logo-light.png') }}" width="177" alt="logo">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-four__form">
                        <div class="sec-title text-start">
                            <div class="sec-title__top justify-content-start">
                                <span class="line-left"></span>
                                <h6 class="sec-title__tagline bw-split-in-right">{{ __('contact.tagline') }}</h6>
                            </div>
                            <h3 class="sec-title__title bw-split-in-left">{{ __('contact.form_title') }}</h3>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST" class="form-one">
                            @csrf
                            <div class="form-one__group">
                                <div class="form-one__control form-one__control--full">
                                    <input type="text" name="name" placeholder="{{ __('contact.field_name') }} *"
                                           value="{{ old('name') }}" required>
                                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-one__control">
                                    <input type="text" name="phone" placeholder="{{ __('contact.field_phone') }}"
                                           value="{{ old('phone') }}">
                                </div>
                                <div class="form-one__control">
                                    <input type="email" name="email" placeholder="{{ __('contact.field_email') }} *"
                                           value="{{ old('email') }}" required>
                                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <input type="text" name="subject" placeholder="{{ __('contact.field_subject') }}"
                                           value="{{ old('subject') }}">
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <textarea name="message" cols="30" rows="10"
                                              placeholder="{{ __('contact.field_message') }} *" required>{{ old('message') }}</textarea>
                                    @error('message')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <button type="submit" class="zoomvilla-btn">
                                        {{ __('contact.send_btn') }} <i class="icon-angle-small-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="contact-four__bottom">
            <div class="contact-four__call">
                <div class="contact-four__call__icon">
                    <i class="icon-phone-call"></i>
                </div>
                <h4 class="contact-four__call__text">
                    {{ __('contact.call_text') }}
                    <a href="tel:{{ config('contact.phone', '') }}">{{ config('contact.phone', '+372 000 000') }}</a>
                </h4>
            </div>
            <a href="tel:{{ config('contact.phone', '') }}" class="zoomvilla-btn">
                {{ __('contact.call_btn') }} <i class="icon-angle-small-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection

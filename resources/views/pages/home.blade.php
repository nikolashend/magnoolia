@extends('layouts.app')

@section('title', __('home.meta_title'))
@section('meta_description', __('home.meta_description'))
@section('body_class', 'custom-cursor')

@section('content')
    @include('partials.home.slider')
    @include('partials.home.categories')
    @include('partials.home.about')
    @include('partials.home.testimonials')
@endsection

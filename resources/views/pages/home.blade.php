@extends('layouts.app')

@section('title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('meta_description', 'Magnoolia on premium uusarendus Vaelas, Kiili vallas — 19 A-energiaklassi kodu privaatse hoovi, terrassi ja Tallinna lähedusega. Valmib suvi 2027.')
@section('og_title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('body_class', 'custom-cursor')

@section('content')
    @include('partials.home.slider')
    @include('partials.home.categories')
    @include('partials.home.about')
    @include('partials.home.testimonials')
@endsection

@extends('layouts.app')

@section('title', $service->title)

@section('content')
    @include('partials.page-header', ['title' => $service->title, 'breadcrumbs' => [
        ['label' => __('menu.home'), 'url' => route('home')],
        ['label' => __('menu.services'), 'url' => route('services.index')],
        ['label' => $service->title],
    ]])

    <section class="service-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    @if($service->thumbnail)
                        <div class="service-details__img mb-4">
                            <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="img-fluid">
                        </div>
                    @endif
                    <h2>{{ $service->title }}</h2>
                    <div class="service-details__text">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection

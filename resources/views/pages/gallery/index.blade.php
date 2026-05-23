@extends('layouts.app')

@section('title', __('gallery.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('gallery.page_title'),
    'breadcrumbs' => [__('gallery.page_title') => route('gallery.index')],
])

<section class="gallery-page section-space">
    <div class="container">
        <div class="row gutter-y-20" id="gallery-grid">
            @forelse($images as $image)
            <div class="col-md-4 col-sm-6">
                <div class="gallery-item">
                    <a href="{{ $image->url }}" class="popup-image">
                        <img src="{{ $image->url }}" alt="{{ $image->caption ?? __('gallery.image') }}">
                        <div class="gallery-item__overlay">
                            <i class="icon-search"></i>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>{{ __('gallery.no_images') }}</p>
            </div>
            @endforelse
        </div>

        <div class="mt-40 d-flex justify-content-center">
            {{ $images->links() }}
        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', $apartment->title . ' - ' . config('app.name'))
@section('meta_description', $apartment->description)
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => $apartment->title,
    'breadcrumbs' => [
        __('apartments.page_title') => route('apartments.index'),
        $apartment->title => route('apartments.show', $apartment->slug),
    ],
])

<section class="apartment-details section-space">
    <div class="container">
        <div class="row gutter-y-40">

            <div class="col-lg-8">

                {{-- Image Gallery --}}
                <div class="apartment-details__gallery mb-40">
                    <div class="apartment-details__gallery__main hover:shine">
                        <img src="{{ $apartment->main_image_url }}" alt="{{ $apartment->title }}">
                    </div>
                    @if($apartment->images->count() > 1)
                    <div class="apartment-details__gallery__thumbs mt-20 row gutter-y-10">
                        @foreach($apartment->images->skip(1) as $image)
                        <div class="col-md-3">
                            <a href="{{ $image->url }}" class="popup-image">
                                <img src="{{ $image->url }}" alt="{{ $apartment->title }}">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="apartment-details__info">
                    <h2>{{ $apartment->title }}</h2>
                    <p class="apartment-details__location"><i class="icon-pin"></i> {{ $apartment->address }}</p>

                    <div class="apartment-details__features row gutter-y-20 mt-20">
                        <div class="col-md-4">
                            <div class="apartment-details__feature-item">
                                <i class="icon-bedroom"></i>
                                <span>{{ $apartment->rooms }} {{ __('apartments.rooms') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="apartment-details__feature-item">
                                <i class="icon-bath"></i>
                                <span>{{ $apartment->bathrooms }} {{ __('apartments.bathrooms') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="apartment-details__feature-item">
                                <i class="icon-labyrinth"></i>
                                <span>{{ $apartment->area }} m²</span>
                            </div>
                        </div>
                    </div>

                    <div class="apartment-details__description mt-40">
                        <h4>{{ __('apartments.description') }}</h4>
                        <div>{!! $apartment->description !!}</div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="apartment-details__sidebar">
                    <div class="apartment-details__price-box">
                        <span class="apartment-details__price">{{ $apartment->formatted_price }}</span>
                        <span class="apartment-details__status">{{ $apartment->status_label }}</span>
                    </div>

                    <form action="{{ route('apartments.inquiry') }}" method="POST" class="form-one mt-30">
                        @csrf
                        <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                        <div class="form-one__group">
                            <div class="form-one__control form-one__control--full">
                                <input type="text" name="name" placeholder="{{ __('contact.field_name') }} *" required>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <input type="email" name="email" placeholder="{{ __('contact.field_email') }} *" required>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <input type="text" name="phone" placeholder="{{ __('contact.field_phone') }}">
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <textarea name="message" rows="5" placeholder="{{ __('contact.field_message') }}"></textarea>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <button type="submit" class="zoomvilla-btn w-100">
                                    {{ __('apartments.send_inquiry') }} <i class="icon-angle-small-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

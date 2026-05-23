@extends('layouts.app')

@section('title', __('faq.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

@include('partials.page-header', [
    'pageTitle' => __('faq.page_title'),
    'breadcrumbs' => [__('faq.page_title') => route('faq')],
])

<section class="faq-page section-space">
    <div class="container">
        <div class="row gutter-y-40">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    @forelse($faqs as $index => $faq)
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $index }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                             aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p>{{ __('faq.no_faqs') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

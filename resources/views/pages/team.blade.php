@extends('layouts.app')

@section('title', __('menu.team'))

@section('content')
    @include('partials.page-header', ['title' => __('menu.team'), 'breadcrumbs' => [['label' => __('menu.home'), 'url' => route('home')], ['label' => __('menu.team')]]])

    <section class="team-one">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">{{ __('common.our_team') }}</h6>
                <h3 class="sec-title__title">{{ __('common.meet_the_team') }}</h3>
            </div>
            <div class="row">
                @forelse($teamMembers ?? [] as $member)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="team-one__single">
                            <div class="team-one__img">
                                <img src="{{ $member['photo'] ?? asset('assets/images/team/team-placeholder.jpg') }}" alt="{{ $member['name'] }}">
                            </div>
                            <div class="team-one__content">
                                <h3>{{ $member['name'] }}</h3>
                                <p>{{ $member['position'] }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">{{ __('common.coming_soon') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

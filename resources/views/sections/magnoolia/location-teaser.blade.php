{{--
    location-teaser.blade.php — homepage "Looduse rahu, linna lähedus" block.
    Slogan + a few key distance highlights (reusing the real /asukoht distance data,
    so there is one source of truth), linking to /asukoht for the full table.
--}}
@php
  // Only the destinations that have a confirmed time (skip the "täpsustub"/— rows).
  $locDists = collect(__('magnoolia.page.asukoht.distances'))
      ->filter(fn ($d) => is_array($d) && ($d['time'] ?? '—') !== '—' && ($d['time'] ?? '') !== '')
      ->take(5)->values();
@endphp

<section class="mg-page-section mg-page-section--cream" id="asukoht-teaser" style="scroll-margin-top:150px;">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.home_location.eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.home_location.title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.home_location.sub') }}</p>
    </div>

    @if($locDists->isNotEmpty())
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;max-width:940px;margin:0 auto;">
      @foreach($locDists as $d)
      <div style="background:#fff;border:1px solid rgba(29,36,48,.08);border-radius:14px;padding:18px 14px;text-align:center;">
        <div style="font-size:13.5px;font-weight:700;color:#1d2430;margin-bottom:8px;min-height:34px;display:flex;align-items:center;justify-content:center;">{{ $d['dest'] }}</div>
        <div style="font-size:22px;font-weight:800;color:#c89443;line-height:1;">{{ $d['time'] }}</div>
        <div style="font-size:12px;color:#9a9490;margin-top:5px;">{{ $d['dist'] }}</div>
      </div>
      @endforeach
    </div>
    @endif

    <div style="text-align:center;margin-top:34px;">
      <a href="{{ lroute('magnoolia.location') }}" class="zoomvilla-btn">{{ __('magnoolia.home_location.cta') }} <i class="icon-angle-small-right"></i></a>
    </div>
  </div>
</section>

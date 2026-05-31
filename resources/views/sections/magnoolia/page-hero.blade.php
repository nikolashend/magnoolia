{{--
  page-hero.blade.php — Phase 15 reusable dark hero
  Props:
    $eyebrow   — small label above title
    $title     — H1 (usually passed as @slot or variable)
    $lead      — lead paragraph
    $note      — optional small disclaimer
    $ctas      — array of ['label', 'url', 'outline' => bool, 'tel' => bool]
    $breadcrumbs — passed to breadcrumb partial
--}}
<div class="mg-page-hero">
  <div class="container">

    @if(!empty($breadcrumbs))
      @include('partials.seo.breadcrumb', ['items' => $breadcrumbs])
    @endif

    @if(!empty($eyebrow))
      <div class="mg-page-hero__eyebrow">{{ $eyebrow }}</div>
    @endif

    @if(!empty($title))
      <h1 class="mg-page-hero__title">{!! $title !!}</h1>
    @endif

    @if(!empty($lead))
      <p class="mg-page-hero__lead">{!! $lead !!}</p>
    @endif

    @if(!empty($note))
      <p class="mg-page-hero__note">{{ $note }}</p>
    @endif

    @if(!empty($ctas))
      <div class="mg-page-hero__ctas">
        @foreach($ctas as $cta)
          <a href="{{ $cta['url'] }}"
             class="{{ ($cta['outline'] ?? false) ? 'zoomvilla-btn zoomvilla-btn--border' : 'zoomvilla-btn' }}"
             data-event="hero_cta" data-label="{{ $cta['label'] }}"
             @if(!empty($cta['tel'])) style="display:flex;align-items:center;gap:8px;" @endif>
            @if(!empty($cta['icon']))<i class="{{ $cta['icon'] }}"></i>@endif
            {{ $cta['label'] }}
            @if(empty($cta['tel']))<i class="icon-angle-small-right"></i>@endif
          </a>
        @endforeach
      </div>
    @endif

  </div>
</div>

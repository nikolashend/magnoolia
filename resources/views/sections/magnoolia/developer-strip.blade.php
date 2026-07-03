{{--
    developer-strip.blade.php — homepage "Arendaja" band with the large Estlanda logo.
    Client asked for a bigger Estlanda logo on the homepage (previously only small in the footer).
--}}
<section class="mg-page-section mg-page-section--cream" id="arendaja" style="scroll-margin-top:150px;">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:center;gap:56px;flex-wrap:wrap;">

      <div style="flex:0 1 220px;min-width:170px;text-align:center;">
        <a href="{{ lroute('magnoolia.developer') }}" aria-label="Estlanda" style="display:inline-block;">
          <picture>
            <source srcset="{{ asset('assets/magnoolia/logos/estlanda-1.webp') }}" type="image/webp">
            <img src="{{ asset('assets/magnoolia/logos/estlanda-1.png') }}"
                 alt="Estlanda Ehitus"
                 width="190" height="110"
                 loading="lazy" decoding="async"
                 style="width:100%;max-width:190px;height:auto;display:inline-block;filter:brightness(0);opacity:.88;">
          </picture>
        </a>
      </div>

      <div style="flex:1 1 420px;min-width:280px;max-width:520px;">
        <div class="mg-section-heading__eyebrow">{{ __('magnoolia.home_developer.eyebrow') }}</div>
        <h2 class="mg-section-heading__title" style="margin:6px 0 14px;">{{ __('magnoolia.home_developer.title') }}</h2>
        <p style="font-size:16px;line-height:1.7;color:#4a4540;margin:0 0 22px;">{{ __('magnoolia.home_developer.body') }}</p>
        <a href="{{ lroute('magnoolia.developer') }}" class="zoomvilla-btn">{{ __('magnoolia.home_developer.cta') }} <i class="icon-angle-small-right"></i></a>
      </div>

    </div>
  </div>
</section>

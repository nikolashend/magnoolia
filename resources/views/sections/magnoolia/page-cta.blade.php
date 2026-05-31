{{--
  page-cta.blade.php — Phase 15 reusable dark CTA strip
  Props:
    $title   — heading
    $sub     — sub text
    $buttons — array of ['label', 'url', 'outline' => bool, 'icon' => 'fas fa-phone']
--}}
<section class="mg-page-cta">
  <div class="container">
    <h2 class="mg-page-cta__title">{{ $title ?? 'Küsi lähemalt' }}</h2>
    @if(!empty($sub))
      <p class="mg-page-cta__sub">{{ $sub }}</p>
    @endif
    <div class="mg-page-cta__btns">
      @foreach($buttons ?? [] as $btn)
        <a href="{{ $btn['url'] }}"
           class="{{ ($btn['outline'] ?? false) ? 'zoomvilla-btn zoomvilla-btn--border' : 'zoomvilla-btn' }}">
          @if(!empty($btn['icon']))<i class="{{ $btn['icon'] }}" style="margin-right:7px;"></i>@endif
          {{ $btn['label'] }}
          @if(empty($btn['icon']))<i class="icon-angle-small-right"></i>@endif
        </a>
      @endforeach
    </div>
  </div>
</section>

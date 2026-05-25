@props(['title'=>'', 'subtitle'=>'', 'primaryHref'=>'#', 'primaryLabel'=>'Küsi pakkumist', 'secondaryHref'=>'', 'secondaryLabel'=>''])
<div class="mg-cta-panel">
  <div>
    <h2 class="mg-cta-panel__title">{{ $title }}</h2>
    @if($subtitle)<p class="mg-cta-panel__sub">{{ $subtitle }}</p>@endif
  </div>
  <div class="mg-cta-panel__actions">
    <a href="{{ $primaryHref }}" class="mg-btn mg-btn--gold mg-btn--lg">{{ $primaryLabel }}</a>
    @if($secondaryHref)
      <a href="{{ $secondaryHref }}" class="mg-btn mg-btn--ghost mg-btn--lg">{{ $secondaryLabel }}</a>
    @endif
  </div>
</div>

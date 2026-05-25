@props(['value'=>'', 'label'=>'', 'icon'=>''])
<div class="mg-fact-card">
  @if($icon)
    <div class="mg-benefit-card__icon" style="margin:0 auto 12px;"><i class="{{ $icon }}" aria-hidden="true"></i></div>
  @endif
  <span class="mg-fact-card__value">{{ $value }}</span>
  <span class="mg-fact-card__label">{{ $label }}</span>
</div>

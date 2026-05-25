@props(['icon'=>'fas fa-check', 'title'=>'', 'text'=>''])
<div class="mg-benefit-card">
  <div class="mg-benefit-card__icon"><i class="{{ $icon }}" aria-hidden="true"></i></div>
  <h3 class="mg-benefit-card__title">{{ $title }}</h3>
  <p class="mg-benefit-card__text">{{ $text ?: $slot }}</p>
</div>

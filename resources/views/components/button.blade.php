@props(['href'=>'#', 'label'=>'', 'variant'=>'primary', 'size'=>'', 'type'=>'a', 'ctaId'=>''])
@if($type === 'button')
  <button
    class="mg-btn mg-btn--{{ $variant }} {{ $size ? 'mg-btn--'.$size : '' }}"
    {{ $ctaId ? 'data-cta-id='.$ctaId : '' }}
    {{ $attributes }}>{{ $label ?: $slot }}</button>
@else
  <a
    href="{{ $href }}"
    class="mg-btn mg-btn--{{ $variant }} {{ $size ? 'mg-btn--'.$size : '' }}"
    {{ $ctaId ? 'data-cta-id='.$ctaId : '' }}
    {{ $attributes }}>{{ $label ?: $slot }}</a>
@endif

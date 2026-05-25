@props(['src'=>'', 'alt'=>'', 'ratio'=>'4/3', 'rounded'=>'lg'])
<div class="mg-image-frame" style="aspect-ratio:{{ $ratio }}; border-radius:var(--radius-{{ $rounded }});">
  @if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy">
  @else
    <div class="mg-image-frame--placeholder">
      <i class="fas fa-image"></i>
      <span>{{ $alt ?: '[Render tuleb]' }}</span>
    </div>
  @endif
</div>

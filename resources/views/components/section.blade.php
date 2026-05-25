@props(['id'=>'', 'eyebrow'=>'', 'title'=>'', 'description'=>'', 'theme'=>'white', 'centered'=>false, 'narrow'=>false])
<section 
    class="mg-section mg-section--{{ $theme }} {{ $centered ? 'text-center' : '' }}"
    {{ $id ? 'id='.$id : '' }}
    {{ $attributes }}>
  <div class="mg-container">
    @if($eyebrow)
      <div class="mg-eyebrow {{ $centered ? 'd-block text-center w-100 justify-content-center' : '' }}">{{ $eyebrow }}</div>
    @endif
    @if($title)
      <h2 class="mg-section__title {{ $centered ? 'mg-section__title--centered' : '' }}" style="{{ $narrow ? 'max-width:540px;' : '' }}">
        {!! $title !!}
      </h2>
    @endif
    @if($description)
      <p class="mg-section__lead {{ $centered ? 'mg-section__lead--centered' : '' }}">{{ $description }}</p>
    @endif
    {{ $slot }}
  </div>
</section>

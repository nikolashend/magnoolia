@props(['name'=>'', 'role'=>'', 'photo'=>'', 'phone'=>'', 'email'=>''])
<div class="mg-contact-card">
  <div class="mg-contact-card__photo">
    @if($photo)<img src="{{ $photo }}" alt="{{ $name }}">
    @else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--mg-soft-grey);"><i class="fas fa-user" style="font-size:32px;color:var(--mg-warm-grey);"></i></div>
    @endif
  </div>
  <div class="mg-contact-card__name">{{ $name ?: '[Nimi tuleb]' }}</div>
  <div class="mg-contact-card__role">{{ $role }}</div>
  <div class="mg-contact-card__contacts">
    @if($phone)<a href="tel:{{ $phone }}"><i class="fas fa-phone"></i>{{ $phone }}</a>@endif
    @if($email)<a href="mailto:{{ $email }}"><i class="fas fa-envelope"></i>{{ $email }}</a>@endif
  </div>
</div>

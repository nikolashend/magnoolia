@props(['status'=>'available'])
@php
$map = [
    'available'  => ['class'=>'mg-badge--available', 'label'=>'Saadaval'],
    'reserved'   => ['class'=>'mg-badge--reserved',  'label'=>'Broneeritud'],
    'sold'       => ['class'=>'mg-badge--sold',       'label'=>'Müüdud'],
    'new'        => ['class'=>'mg-badge--new',        'label'=>'Uus'],
    'energy'     => ['class'=>'mg-badge--energy',     'label'=>'A-energiaklass'],
];
$cfg = $map[$status] ?? $map['available'];
@endphp
<span class="mg-badge {{ $cfg['class'] }}">{{ $slot ?? $cfg['label'] }}</span>

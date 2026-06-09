{{--
    magnoolia/icon.blade.php
    Inline SVG icon component. All icons share the same stroke style.

    Props:
    - $name (string) — icon name (see allowed list below)
    - $size (int)    — size in px, default 20
    - $class (string)— extra CSS classes
--}}
@props(['name' => 'home', 'size' => 20, 'class' => ''])
@php
$icons = [
  'home'      => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
  'leaf'      => '<path d="M2 22C2 22 7 22 12 17S22 2 22 2C22 2 22 7 17 12S2 22 2 22Z"/><line x1="12" y1="12" x2="2" y2="22"/>',
  'school'    => '<path d="M2 20h20"/><path d="M6 20V10"/><path d="M18 20V10"/><path d="M12 4L2 10h20L12 4z"/>',
  'shopping'  => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
  'sport'     => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
  'car'       => '<rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
  'map-pin'   => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
  'bike'      => '<circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><path d="M15 6h-5l-3 11.5"/><path d="M12 6l2 4-5.5 3.5"/><path d="M18.5 17.5L13 9"/>',
  'forest'    => '<path d="M17 20H7"/><path d="M12 20V8"/><path d="M7 14l5-6 5 6"/><path d="M5 18l7-9 7 9"/>',
  'family'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
  'energy'    => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
  'terrace'   => '<path d="M3 21h18"/><path d="M5 21V9"/><path d="M19 21V9"/><path d="M9 21v-5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v5"/><path d="M2 10l10-7 10 7"/>',
  'parking'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/>',
  'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.57a16 16 0 0 0 6.29 6.29l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
  'mail'      => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
  'download'  => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
  'floorplan' => '<rect x="3" y="3" width="18" height="18" rx="1"/><path d="M3 9h18M9 3v6M9 9h9v9"/>',
  'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
  'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
  'check'     => '<polyline points="20 6 9 17 4 12"/>',
  'arrow'     => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
];
$path = $icons[$name] ?? $icons['home'];
@endphp
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="{{ $class }}" aria-hidden="true" focusable="false"
     {{ $attributes }}>{!! $path !!}</svg>

{{--
    sections/magnoolia/lifestyle-proof.blade.php
    Phase 26 — Homepage lifestyle/location proof block.
    4 cards max. No duplication of full Asukoht page.
--}}
@php
  $locale = app()->getLocale();
  $cards = [
    [
      'img'  => 'vaela-lasteaed.webp',
      'alt_et' => 'Vaela lasteaed Magnoolia kodude lähedal',
      'alt_ru' => 'Детский сад Vaela рядом с Magnoolia',
      'alt_en' => 'Vaela kindergarten near Magnoolia',
      'title_et' => 'Vaela lasteaed',
      'title_ru' => 'Детский сад Vaela',
      'title_en' => 'Vaela Kindergarten',
      'body_et'  => 'Lasteaed kõnnimatka kaugusel koduuksest.',
      'body_ru'  => 'Детский сад в шаговой доступности.',
      'body_en'  => 'Kindergarten within walking distance.',
    ],
    [
      'img'  => 'kiili-spordimaja.jpg',
      'alt_et' => 'Kiili spordihoone ja kool',
      'alt_ru' => 'Спортивный зал и школа Kiili',
      'alt_en' => 'Kiili school and sports centre',
      'title_et' => 'Kiili kool ja spordihoone',
      'title_ru' => 'Школа и спортзал Kiili',
      'title_en' => 'Kiili School & Sports Centre',
      'body_et'  => 'Mitmekesine hariduse ja spordi infrastruktuur.',
      'body_ru'  => 'Разнообразная инфраструктура для учёбы и спорта.',
      'body_en'  => 'Diverse education and sport infrastructure.',
    ],
    [
      'img'  => 'kurna-park.jpg',
      'alt_et' => 'IKEA, Kurna Park ja Selver lähedal',
      'alt_ru' => 'IKEA, Kurna Park и Selver поблизости',
      'alt_en' => 'IKEA, Kurna Park and Selver nearby',
      'title_et' => 'IKEA, Kurna Park ja Selver',
      'title_ru' => 'IKEA, Kurna Park и Selver',
      'title_en' => 'IKEA, Kurna Park & Selver',
      'body_et'  => 'Igapäevased ostud ja suuremad ostukeskused käeulatuses.',
      'body_ru'  => 'Повседневные покупки и крупные центры под рукой.',
      'body_en'  => 'Everyday shopping and major centres at hand.',
    ],
    [
      'img'  => 'kergliiklusteed.jpg',
      'alt_et' => 'Kergliiklusteed ja loodus Kiili ümbruses',
      'alt_ru' => 'Велодорожки и природа вокруг Kiili',
      'alt_en' => 'Cycle paths and nature around Kiili',
      'title_et' => 'Kergliiklusteed ja loodus',
      'title_ru' => 'Велодорожки и природа',
      'title_en' => 'Cycle Paths & Nature',
      'body_et'  => 'Aktiivne eluviis otse ukse taga.',
      'body_ru'  => 'Активный образ жизни прямо у порога.',
      'body_en'  => 'Active lifestyle right at your doorstep.',
    ],
  ];
@endphp

<section class="section-space" style="background:#f8f7f4;">
  <div class="container">
    <div style="text-align:center;margin-bottom:48px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#c89443;margin-bottom:12px;">
        @if($locale==='ru') Расположение @elseif($locale==='en') Location @else Asukoht @endif
      </div>
      <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1d2430;margin-bottom:12px;">
        @if($locale==='ru') Спокойный дом рядом с Таллинном
        @elseif($locale==='en') A peaceful home near Tallinn
        @else Rahulik kodu Tallinna lähedal
        @endif
      </h2>
      <p style="font-size:15px;color:#6f6a61;max-width:560px;margin:0 auto;">
        @if($locale==='ru') Vaela küla и Kiili vald — растущий район Харьюмаа с отличной инфраструктурой.
        @elseif($locale==='en') Vaela village and Kiili municipality — a growing area of Harju County with excellent infrastructure.
        @else Vaela küla ja Kiili vald — kasvav Harjumaa piirkond suurepärase infrastruktuuriga.
        @endif
      </p>
    </div>

    <div class="row gutter-y-24">
      @foreach($cards as $i => $card)
      <div class="col-lg-3 col-md-6">
        <div style="border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 2px 20px rgba(0,0,0,.06);height:100%;">
          @if(file_exists(public_path('assets/magnoolia/location/' . $card['img'])))
          <img src="{{ asset('assets/magnoolia/location/' . $card['img']) }}"
               alt="{{ $card['alt_' . $locale] ?? $card['alt_et'] }}"
               width="320" height="200"
               loading="{{ $i < 2 ? 'eager' : 'lazy' }}"
               decoding="async"
               style="width:100%;height:180px;object-fit:cover;display:block;">
          @endif
          <div style="padding:20px;">
            <div style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:6px;">
              {{ $card['title_' . $locale] ?? $card['title_et'] }}
            </div>
            <div style="font-size:13px;color:#6f6a61;line-height:1.5;">
              {{ $card['body_' . $locale] ?? $card['body_et'] }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div style="text-align:center;margin-top:36px;">
      <a href="{{ lroute('magnoolia.location') }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#1d2430;color:#fff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:700;letter-spacing:.03em;">
        @if($locale==='ru') Узнать об окрестностях
        @elseif($locale==='en') Explore the location
        @else Vaata asukohta
        @endif
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</section>

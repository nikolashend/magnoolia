{{--
  page-faq.blade.php — Phase 15 reusable FAQ section
  Props:
    $faqs    — array of ['q' => '...', 'a' => '...']
    $eyebrow — optional label
    $title   — section title
    $bg      — 'warm' | 'white' | 'cream' (default 'warm')
--}}
@php $bg = $bg ?? 'warm'; @endphp
<section class="mg-page-section mg-page-section--{{ $bg }}">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center">
      <div class="mg-section-heading__eyebrow">{{ $eyebrow ?? 'KKK' }}</div>
      <h2 class="mg-section-heading__title">{{ $title ?? 'Korduma kippuvad küsimused' }}</h2>
    </div>
    <div class="row gutter-y-16" itemscope itemtype="https://schema.org/FAQPage">
      @foreach($faqs ?? [] as $i => $faq)
        <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="700ms" data-wow-delay="{{ $i * 70 }}ms"
             itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
          <div class="mg-faq-card">
            <h3 itemprop="name" class="mg-faq-card__q">{{ $faq['q'] }}</h3>
            <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
              <p itemprop="text" class="mg-faq-card__a">{{ $faq['a'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

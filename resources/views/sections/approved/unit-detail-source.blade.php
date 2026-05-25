{{--
  SOURCE MAPPING — SCREENSHOT 06
  Screenshot:   06-unit-details-plan-description-layout.png
  Original demo: https://bracketweb.com/zoomvilla-php/apartments-details.php
  Source file:  php-template/apartments-details.php
  Original class: .apartment-details-one, .apartment-details-one__list, .apartment-details-one__list-content
  CSS required: zoomvilla.css (.apartment-details-one, .apartment-details-one__list-box, .apartment-details-one__img-box)
  JS required:  jquery-magnific-popup.js (for image lightbox), wow.js
  Images:       Unit render + floor plan — [PLACEHOLDER]
  Reuse as-is:  YES (structure) — horizontal spec bar + main image + description layout is perfect
  Rebuild:      PARTIAL — stripped demo content, adapted for single-unit detail view
  Risk:         LOW — static page layout
--}}

{{-- Used on /apartments/{unit} detail page --}}

<section class="apartment-details-one section-space">
    <div class="container">
        <div class="col-xl-12">

            {{-- Main render image --}}
            <div class="apartment-details-one__img-box">
                <div class="apartment-details-one__img hover:shine">
                    {{-- [PLACEHOLDER] Replace with $unit->render_image --}}
                    <div style="width:100%; height:400px; background:var(--mg-soft-grey);
                                border-radius:var(--mg-radius-md); display:flex; align-items:center;
                                justify-content:center; flex-direction:column; gap:12px;">
                        <i class="fas fa-image" style="font-size:64px; color:var(--mg-warm-grey);"></i>
                        <span style="color:var(--mg-warm-grey);">[Render: {{ $unit->name ?? 'Tüüp A' }}]</span>
                    </div>
                </div>
            </div>

            {{-- Spec bar: key numbers --}}
            <div class="apartment-details-one__list-box">
                <ul class="list-unstyled apartment-details-one__list">
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>{{ $unit->area ?? '[m²]' }}</h3>
                            <h5>Elamispind</h5>
                        </div>
                    </li>
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>{{ $unit->bedrooms ?? '[arv]' }}</h3>
                            <h5>Magamistoad</h5>
                        </div>
                    </li>
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>{{ $unit->bathrooms ?? '[arv]' }}</h3>
                            <h5>Vannitoad</h5>
                        </div>
                    </li>
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>{{ $unit->terrace_area ?? '[m²]' }}</h3>
                            <h5>Terrass</h5>
                        </div>
                    </li>
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>{{ $unit->yard_area ?? '600–1200' }} m²</h3>
                            <h5>Hooviala</h5>
                        </div>
                    </li>
                    <li>
                        <div class="apartment-details-one__list-content">
                            <h3>A</h3>
                            <h5>Energiaklass</h5>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Description + floor plan side by side --}}
            <div class="apartment-details-one__content-box">
                <div class="row gutter-y-30">
                    <div class="col-lg-7">
                        <h3 class="apartment-details-one__content-title">
                            {{ $unit->name ?? '[Kodu nimetus]' }}
                        </h3>
                        <p class="apartment-details-one__text-1">
                            {{ $unit->description ?? '[PLACEHOLDER] Kodu kirjeldus lisatakse peagi.' }}
                        </p>

                        {{-- Features checklist --}}
                        <ul class="list-unstyled" style="margin-top:24px;">
                            <li><i class="icon-check-1"></i> Maasoojuspump</li>
                            <li><i class="icon-check-1"></i> Soojustagastusega ventilatsioon</li>
                            <li><i class="icon-check-1"></i> Päikesepaneeli valmidus</li>
                            <li><i class="icon-check-1"></i> EV laadimise valmidus</li>
                            <li><i class="icon-check-1"></i> Katusealune parkimine</li>
                        </ul>

                        <div style="margin-top:32px; display:flex; gap:12px; flex-wrap:wrap;">
                            <a href="{{ route('contact') }}" class="zoomvilla-btn">
                                Küsi pakkumist <i class="icon-angle-small-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        {{-- Floor plan --}}
                        <div style="background:var(--mg-soft-grey); border-radius:var(--mg-radius-md);
                                    padding:24px; display:flex; align-items:center; justify-content:center;
                                    flex-direction:column; gap:12px; min-height:300px;">
                            <i class="fas fa-drafting-compass" style="font-size:48px; color:var(--mg-warm-grey);"></i>
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-sm);">[Korruse plaan]</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

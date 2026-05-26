{{-- ══════════════════════════════════════════════════════════════
    ASENDIPLAAN — Static masterplan section
    Phase 4: static image + lightbox + legend + CTA
    Phase 5: interactive SVG with hotspots per unit
    ══════════════════════════════════════════════════════════════ --}}
<section class="section-space" id="asendiplaan" style="background:#151515;">
    <div class="container">

        {{-- Section header --}}
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left" style="background:rgba(255,255,255,.2);"></span>
                <h6 class="sec-title__tagline bw-split-in-right" style="color:#c89443;">{{ __('magnoolia.section.masterplan_eyebrow') }}</h6>
                <span class="line-right" style="background:rgba(255,255,255,.2);"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left" style="color:#fff;">{{ __('magnoolia.section.masterplan_title') }}</h3>
            <p style="color:rgba(255,255,255,.6);margin-top:16px;font-size:16px;max-width:600px;margin-left:auto;margin-right:auto;">
                {{ __('magnoolia.section.masterplan_body') }}
            </p>
        </div>

        {{-- Masterplan image --}}
        <div class="row align-items-center gutter-y-40">
            <div class="col-lg-8 wow fadeInLeft" data-wow-duration="1400ms">
                {{-- Phase 5 TODO: replace with interactive SVG mastplan --}}
                {{-- data-masterplan="magnoolia" attribute reserved for future hotspots --}}
                <div class="mg-masterplan" data-masterplan="magnoolia" style="position:relative;border-radius:20px;overflow:hidden;">
                    <a href="{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}" class="img-popup">
                        <img src="{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}"
                             alt="Magnoolia 19 kodu asendiplaan Kiili vallas"
                             width="820" height="560"
                             loading="lazy"
                             style="width:100%;height:auto;display:block;object-fit:cover;">
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                    background:rgba(200,148,67,.92);color:#fff;border-radius:50%;
                                    width:60px;height:60px;display:flex;align-items:center;justify-content:center;
                                    font-size:22px;transition:transform .3s;"
                             onmouseover="this.style.transform='translate(-50%,-50%) scale(1.1)'"
                             onmouseout="this.style.transform='translate(-50%,-50%) scale(1)'">
                            <i class="icon-zoom-1"></i>
                        </div>
                    </a>
                    {{-- Phase 5: interactive unit hotspots will be injected here by JS --}}
                </div>
            </div>

            <div class="col-lg-4 wow fadeInRight" data-wow-duration="1400ms" data-wow-delay="200ms">
                <div style="color:#fff;padding:0 0 0 20px;">

                    <h4 style="font-size:22px;font-weight:700;margin-bottom:20px;color:#fff;">
                        Magnoolia arendus kaardil
                    </h4>
                    <p style="color:rgba(255,255,255,.65);font-size:15px;line-height:1.7;margin-bottom:28px;">
                        Igal kodul on oma privaatne hooviala, parkimiskohad ning läbimõeldud ligipääs.
                        Arendus asub Vaela külas, rahulikul uusarenduse alal, ligikaudu 20 minuti kaugusel Tallinnast.
                    </p>

                    {{-- Legend --}}
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:#c89443;flex-shrink:0;"></span>
                            Magnoolia tee — 19 kodu
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:rgba(255,255,255,.3);flex-shrink:0;"></span>
                            Privaatne hooviala igal kodul
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:rgba(255,255,255,.15);flex-shrink:0;border:1px solid rgba(255,255,255,.3);"></span>
                            Parkimiskohad kodu juures
                        </div>
                    </div>

                    {{-- Trust facts --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">19</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">kodu</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">A</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">energiaklass</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">2027</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">valmimine</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">20 min</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">Tallinnast</div>
                        </div>
                    </div>

                    <a href="#hinnad" class="zoomvilla-btn">
                        Vaata kodusid ja hindu <i class="icon-angle-small-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

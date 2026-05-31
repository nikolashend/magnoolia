{{-- ══════════════════════════════════════════════════════════════
    AI ANSWER BLOCK — Google SGE / AI Overview citation-ready
    Structured Q&A in visible text for AI Answers + FAQPage schema
    Entity terms embedded naturally for entity-first SEO
    ══════════════════════════════════════════════════════════════ --}}
<section class="section-space" id="korduma-kippuvad-kusimused" style="background:#fbfaf7;">
    <div class="container">

        <div class="sec-title text-center" style="margin-bottom:48px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.ai_faq.eyebrow') }}</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">{{ __('magnoolia.ai_faq.title') }}</h3>
        </div>

        <div class="row gutter-y-24" itemscope itemtype="https://schema.org/FAQPage">

            @php
            $faqs = __('magnoolia.ai_faq.items');
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="{{ $i * 100 }}ms"
                 itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                <div style="background:#fff;border-radius:16px;padding:28px;height:100%;
                            border:1px solid rgba(29,36,48,.07);transition:box-shadow .25s,transform .25s;"
                     onmouseover="this.style.boxShadow='0 12px 40px rgba(0,0,0,.10)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.boxShadow='none';this.style.transform='none'">
                    <div style="display:flex;gap:16px;align-items:flex-start;">
                        <div style="width:44px;height:44px;border-radius:12px;background:#f7f4ef;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="{{ $faq['icon'] }}" style="font-size:20px;color:#c89443;"></i>
                        </div>
                        <div>
                            <h4 itemprop="name"
                                style="font-size:15px;font-weight:700;color:#1d2430;margin:0 0 10px;">
                                {{ $faq['q'] }}
                            </h4>
                            <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                                <p itemprop="text"
                                   style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">
                                    {{ $faq['a'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        {{-- Hidden semantic entity terms for AI/SGE context — visible for accessibility, muted visually --}}
        <p style="font-size:12px;color:#c4bfb8;text-align:center;margin-top:40px;line-height:1.8;">
            {{ __('magnoolia.ai_faq.seo_note') }}
        </p>

    </div>
</section>

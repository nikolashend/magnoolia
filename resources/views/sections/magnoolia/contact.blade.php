{{-- ══════════════════════════════════════════════════════════════
    CONTACT — Clean contact block without fake team placeholders
    Phase 11: diana_photo_approved + jaanika_confirmed feature flags
    No fake agents, no fake photos. Config-driven only.
    ══════════════════════════════════════════════════════════════ --}}
@php
    $dianaPhotoApproved = config('magnoolia.project.diana_photo_approved', false);
    $jaanikaConfirmed   = config('magnoolia.project.jaanika_confirmed', false);
@endphp
<section class="section-space" id="kontakt" style="background:#151515;position:relative;overflow:hidden;">

    {{-- Background accent image --}}
    <div style="position:absolute;right:0;top:0;bottom:0;width:45%;pointer-events:none;z-index:0;">
        <img src="{{ asset('assets/images/magnoolia/Interior 5-2.jpg') }}"
             alt=""
             aria-hidden="true"
             loading="lazy"
             width="700" height="600"
             style="width:100%;height:100%;object-fit:cover;opacity:0.18;display:block;">
    </div>

    <div class="container" style="position:relative;z-index:1;">
        <div class="row align-items-center gutter-y-40">

            {{-- Left — contact info --}}
            <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1400ms">

                <div class="sec-title text-start">
                    <div class="sec-title__top justify-content-start">
                        <span class="line-left" style="background:rgba(200,148,67,.5);"></span>
                        <h6 class="sec-title__tagline bw-split-in-right" style="color:#c89443;">Võta ühendust</h6>
                    </div>
                    <h3 class="sec-title__title bw-split-in-left" style="color:#fff;">
                        Soovid Magnoolia kodu kohta rohkem teada?
                    </h3>
                </div>

                <p style="color:rgba(255,255,255,.65);font-size:16px;line-height:1.8;margin-bottom:16px;">
                    {{ __('magnoolia.section.contact_body') }}
                </p>
                <p style="color:rgba(255,255,255,.75);font-size:15px;line-height:1.75;margin-bottom:20px;">
                    Kui mõni kodu tundub sobiv, on kõige mõistlikum järgmine samm küsi da konkreetse aadressi saadavust, plaani ja pakkumist.
                    Diana aitab kontrollida, millised kodud on veel vabad ja milline plaan sobib teie perele kõige paremini.
                </p>
                <p style="color:rgba(200,148,67,.8);font-size:14px;line-height:1.6;margin-bottom:32px;font-style:italic;">
                    Küsi vaba kodu, täpsustatud hinda või personaalselt sobivat plaani.
                </p>

                {{-- Contact person --}}
                <div style="background:rgba(255,255,255,.07);border-radius:16px;padding:24px 28px;margin-bottom:32px;border:1px solid rgba(200,148,67,.2);">
                    @if($dianaPhotoApproved)
                    {{-- Diana photo — shown only when diana_photo_approved => true in config --}}
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                        <img src="{{ asset('assets/images/magnoolia/diana-tali.jpg') }}"
                             alt="Diana Tali – Estlanda müügiinfo"
                             width="56" height="56"
                             loading="lazy"
                             style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid rgba(200,148,67,.5);flex-shrink:0;">
                        <div>
                            <div style="font-size:18px;font-weight:700;color:#fff;margin-bottom:2px;">
                                {{ __('magnoolia.contact.name') }}
                            </div>
                            <div style="font-size:13px;color:#c89443;letter-spacing:.04em;text-transform:uppercase;">
                                {{ __('magnoolia.contact.role') }}
                            </div>
                        </div>
                    </div>
                    @else
                    {{-- No-photo elegant layout --}}
                    <div style="font-size:18px;font-weight:700;color:#fff;margin-bottom:4px;">
                        {{ __('magnoolia.contact.name') }}
                    </div>
                    <div style="font-size:13px;color:#c89443;margin-bottom:16px;letter-spacing:.04em;text-transform:uppercase;">
                        {{ __('magnoolia.contact.role') }}
                    </div>
                    @endif
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="tel:+37258164078" style="display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.8);font-size:15px;text-decoration:none;transition:color .2s;"
                           onmouseover="this.style.color='#c89443'" onmouseout="this.style.color='rgba(255,255,255,.8)'">
                            <i class="fas fa-phone" style="color:#c89443;width:16px;"></i>
                            {{ __('magnoolia.contact.phone') }}
                        </a>
                        <a href="mailto:{{ __('magnoolia.contact.email') }}" style="display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.8);font-size:15px;text-decoration:none;transition:color .2s;"
                           onmouseover="this.style.color='#c89443'" onmouseout="this.style.color='rgba(255,255,255,.8)'">
                            <i class="fas fa-envelope" style="color:#c89443;width:16px;"></i>
                            {{ __('magnoolia.contact.email') }}
                        </a>
                    </div>
                </div>

                @if($jaanikaConfirmed)
                {{-- Jaanika mini-card — only when jaanika_confirmed => true in config --}}
                <div style="background:rgba(255,255,255,.05);border-radius:16px;padding:20px 24px;margin-bottom:28px;border:1px solid rgba(255,255,255,.1);">
                    <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:2px;">Jaanika Salumäe</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.5);margin-bottom:12px;text-transform:uppercase;letter-spacing:.04em;">
                        Sisedisaini konsultatsioon · JP Design
                    </div>
                    <a href="#kontakt"
                       style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;
                              color:rgba(255,255,255,.7);text-decoration:none;transition:color .2s;"
                       onmouseover="this.style.color='#c89443'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                        <i class="fas fa-paint-brush" style="color:#c89443;width:14px;"></i>
                        Küsi sisedisaini nõustamist
                    </a>
                </div>
                @endif

                {{-- CTAs --}}
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:36px;">
                    <a href="tel:+37258164078" class="zoomvilla-btn">
                        <i class="fas fa-phone" style="margin-right:8px;"></i>Helista
                    </a>
                    <a href="mailto:diana@estlanda.ee" class="zoomvilla-btn zoomvilla-btn--border">
                        <i class="fas fa-envelope" style="margin-right:8px;"></i>Saada e-kiri
                    </a>
                    <a href="#kontakt" class="zoomvilla-btn zoomvilla-btn--border">
                        Küsi pakkumist <i class="icon-angle-small-right"></i>
                    </a>
                </div>

                {{-- Trust chips --}}
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach([
                        '19 kodu',
                        'A-energiaklass',
                        'I etapp · kevad 2027',
                        'Vaela küla · Kiili vald',
                        'Tallinna lähedal',
                    ] as $chip)
                    <div style="display:inline-flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.7);
                                background:rgba(255,255,255,.08);border-radius:100px;padding:6px 14px;
                                border:1px solid rgba(200,148,67,.25);">
                        <span style="width:5px;height:5px;border-radius:50%;background:#c89443;flex-shrink:0;"></span>
                        {{ $chip }}
                    </div>
                    @endforeach
                </div>

            </div>

            {{-- Right — inquiry form --}}
            <div class="col-lg-5 offset-lg-1 wow fadeInRight" data-wow-duration="1400ms" data-wow-delay="200ms">
                <div style="background:#fff;border-radius:20px;padding:36px;">
                    <h4 style="font-size:20px;font-weight:700;color:#1d2430;margin-bottom:24px;">
                        Küsi pakkumist
                    </h4>

                    <form action="{{ route('contact.send') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;">
                        @csrf

                        {{-- Selected unit (prefilled by modal CTA) --}}
                        @php $allUnitsForForm = config('magnoolia.units', []); @endphp
                        <div>
                            <select name="selected_unit" id="mg-selected-unit-select"
                                    style="width:100%;border:1.5px solid rgba(29,36,48,.15);border-radius:10px;
                                           padding:12px 16px;font-size:14px;color:#1d2430;background:#fff;
                                           outline:none;transition:border-color .2s;font-family:inherit;
                                           appearance:none;-webkit-appearance:none;
                                           background-image:url('data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'><path d=\'M1 1l5 5 5-5\' stroke=\'%239a9490\' stroke-width=\'1.5\' fill=\'none\'/></svg>');
                                           background-repeat:no-repeat;background-position:right 14px center;
                                           padding-right:40px;"
                                    onfocus="this.style.borderColor='#c89443'" onblur="this.style.borderColor='rgba(29,36,48,.15)'">
                                <option value="">Soovitud kodu (valikuline)</option>
                                @foreach($allUnitsForForm as $u)
                                    @if(($u['status'] ?? 'tbc') !== 'sold')
                                    <option value="{{ $u['address'] }}">{{ $u['address'] }} — {{ $u['rooms'] ?? '?' }} tuba, {{ number_format($u['net_area'] ?? 0, 1) }} m²</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <input type="text" name="name" placeholder="Nimi *" required
                                   style="width:100%;border:1.5px solid rgba(29,36,48,.15);border-radius:10px;
                                          padding:12px 16px;font-size:14px;outline:none;transition:border-color .2s;
                                          font-family:inherit;"
                                   onfocus="this.style.borderColor='#c89443'" onblur="this.style.borderColor='rgba(29,36,48,.15)'">
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="E-post *" required
                                   style="width:100%;border:1.5px solid rgba(29,36,48,.15);border-radius:10px;
                                          padding:12px 16px;font-size:14px;outline:none;transition:border-color .2s;
                                          font-family:inherit;"
                                   onfocus="this.style.borderColor='#c89443'" onblur="this.style.borderColor='rgba(29,36,48,.15)'">
                        </div>
                        <div>
                            <input type="tel" name="phone" placeholder="Telefon"
                                   style="width:100%;border:1.5px solid rgba(29,36,48,.15);border-radius:10px;
                                          padding:12px 16px;font-size:14px;outline:none;transition:border-color .2s;
                                          font-family:inherit;"
                                   onfocus="this.style.borderColor='#c89443'" onblur="this.style.borderColor='rgba(29,36,48,.15)'">
                        </div>
                        <div>
                            <textarea name="message" placeholder="Sõnum — näiteks milline kodu huvitab" rows="4"
                                      style="width:100%;border:1.5px solid rgba(29,36,48,.15);border-radius:10px;
                                             padding:12px 16px;font-size:14px;outline:none;transition:border-color .2s;
                                             resize:vertical;font-family:inherit;"
                                      onfocus="this.style.borderColor='#c89443'" onblur="this.style.borderColor='rgba(29,36,48,.15)'"></textarea>
                        </div>
                        {{-- Consent --}}
                        <div style="display:flex;align-items:flex-start;gap:12px;">
                            <input type="checkbox" name="consent" id="mg-consent" required
                                   style="width:16px;height:16px;accent-color:#c89443;cursor:pointer;flex-shrink:0;margin-top:2px;">
                            <label for="mg-consent"
                                   style="font-size:13px;color:#6f6a61;line-height:1.5;cursor:pointer;">
                                Nõustun, et minuga võetakse ühendust seoses Magnoolia kodude kohta info saamisega.
                            </label>
                        </div>

                        <button type="submit" class="zoomvilla-btn" style="width:100%;justify-content:center;border:none;cursor:pointer;">
                            Saada päring <i class="icon-angle-small-right"></i>
                        </button>
                    </form>

                    <p style="font-size:12px;color:#9a9490;text-align:center;margin-top:14px;margin-bottom:0;line-height:1.6;">
                        Pärast päringu saatmist täpsustame valitud kodu saadavuse, plaani, hinna ja järgmise sammu.
                        Kui soovite kiiremat vastust,
                        <a href="tel:+37258164078" style="color:#c89443;text-decoration:none;font-weight:600;">helistage otse Dianale</a>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

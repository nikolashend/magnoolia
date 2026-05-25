{{-- MAGNOOLIA HERO — uses original .main-slider-two structure from Zoomvilla index-2 --}}
{{-- No OWL carousel dependency — single static slide wrapped in .active to trigger visibility CSS --}}
<section class="main-slider-two" id="home">
    <div class="active">
        <div class="main-slider-two__item">

            {{-- Background image placeholder (replace with real render) --}}
            <div class="main-slider-two__bg"
                 style="background-image: url('{{ asset('assets/images/backgrounds/slider-2-1.jpg') }}');">
            </div>

            <div class="container">
                <div class="row gutter-y-40 align-items-end">
                    <div class="col-xl-8 col-lg-9 col-md-12">
                        <div class="main-slider-two__content">

                            <h5 class="main-slider-two__subtitle">
                                <span class="line-left"></span>
                                Vaela küla &middot; Kiili vald &middot; Harjumaa
                            </h5>

                            <h2 class="main-slider-two__title">
                                A-energiaklassi kodud<br>
                                <span>Tallinna lähedal</span>
                            </h2>

                            <p class="main-slider-two__text">
                                Magnoolia ühendab ridaelamu mugavuse, eramaja privaatsuse ja
                                uusarenduse kindluse Vaelas, Kiili vallas — 20 minutit Tallinnast.
                            </p>

                            <div class="main-slider-two__btn">
                                <a href="{{ route('apartments.index') }}" class="zoomvilla-btn">
                                    Vaata kodusid <i class="icon-angle-small-right"></i>
                                </a>
                                <a href="{{ route('contact') }}" class="zoomvilla-btn zoomvilla-btn--border" style="margin-left:16px;">
                                    Küsi pakkumist <i class="icon-angle-small-right"></i>
                                </a>
                            </div>

                            {{-- Quick facts row --}}
                            <div style="display:flex;gap:32px;margin-top:28px;padding-top:24px;border-top:1px solid rgba(30,31,36,0.15);">
                                <div><strong style="font-size:20px;font-weight:700;display:block;color:var(--zoomvilla-black3,#1E1F24);">19</strong><span style="font-size:13px;color:#555;">kodu</span></div>
                                <div><strong style="font-size:20px;font-weight:700;display:block;color:var(--zoomvilla-black3,#1E1F24);">A</strong><span style="font-size:13px;color:#555;">energiaklass</span></div>
                                <div><strong style="font-size:20px;font-weight:700;display:block;color:var(--zoomvilla-black3,#1E1F24);">2027</strong><span style="font-size:13px;color:#555;">valmimisaeg</span></div>
                                <div><strong style="font-size:20px;font-weight:700;display:block;color:var(--zoomvilla-black3,#1E1F24);">20 min</strong><span style="font-size:13px;color:#555;">Tallinnast</span></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
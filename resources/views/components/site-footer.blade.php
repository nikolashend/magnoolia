{{--
    site-footer.blade.php
    Phase 1: minimal placeholder structure. Full footer in Phase 2.
--}}
<footer class="site-footer" role="contentinfo" style="background:var(--color-black);color:var(--color-off-white);padding:var(--space-2xl) 0 var(--space-lg);">
    <div class="container">

        <div style="display:flex;flex-wrap:wrap;gap:var(--space-xl);justify-content:space-between;margin-bottom:var(--space-xl);">

            {{-- Brand column --}}
            <div>
                <div style="font-size:var(--text-lg);font-weight:600;letter-spacing:var(--tracking-wide);margin-bottom:var(--space-sm);">
                    {{ config('magnoolia.project.brand_name') }}
                </div>
                <p style="font-size:var(--text-sm);color:rgba(245,243,240,0.6);max-width:280px;line-height:var(--leading-normal);">
                    {{ config('magnoolia.project.location') }}
                </p>
            </div>

            {{-- Contact placeholder --}}
            <div>
                <p style="font-size:var(--text-xs);letter-spacing:var(--tracking-wider);text-transform:uppercase;color:var(--color-muted-gold);margin-bottom:var(--space-sm);">Kontakt</p>
                <p style="font-size:var(--text-sm);color:rgba(245,243,240,0.7);margin:0 0 4px;">
                    <a href="tel:{{ config('magnoolia.project.contact_phone') }}"
                       style="color:inherit;text-decoration:none;">
                        {{ config('magnoolia.project.contact_phone') }}
                    </a>
                </p>
                <p style="font-size:var(--text-sm);color:rgba(245,243,240,0.7);margin:0;">
                    <a href="mailto:{{ config('magnoolia.project.contact_email') }}"
                       style="color:inherit;text-decoration:none;">
                        {{ config('magnoolia.project.contact_email') }}
                    </a>
                </p>
            </div>

            {{-- Developer placeholder --}}
            <div>
                <p style="font-size:var(--text-xs);letter-spacing:var(--tracking-wider);text-transform:uppercase;color:var(--color-muted-gold);margin-bottom:var(--space-sm);">Arendaja</p>
                <p style="font-size:var(--text-sm);color:rgba(245,243,240,0.7);margin:0;">
                    {{ config('magnoolia.project.developer') }}
                </p>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:var(--space-md);display:flex;flex-wrap:wrap;gap:var(--space-md);justify-content:space-between;align-items:center;">
            <p style="font-size:var(--text-xs);color:rgba(245,243,240,0.4);margin:0;">
                &copy; {{ date('Y') }} {{ config('magnoolia.project.brand_name') }}. Kõik õigused kaitstud.
            </p>
            <nav aria-label="Juriidilised lingid">
                <ul style="list-style:none;margin:0;padding:0;display:flex;gap:var(--space-md);">
                    <li><a href="#" style="font-size:var(--text-xs);color:rgba(245,243,240,0.4);text-decoration:none;">Privaatsuspoliitika</a></li>
                    <li><a href="#" style="font-size:var(--text-xs);color:rgba(245,243,240,0.4);text-decoration:none;">Kasutustingimused</a></li>
                </ul>
            </nav>
            <x-language-switcher dark />
        </div>

    </div>
</footer>

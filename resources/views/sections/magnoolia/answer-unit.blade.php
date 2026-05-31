{{-- ══════════════════════════════════════════════════════════════
    ANSWER UNIT — AI-citable factual block
    Renders a visible semantic answer block that AI systems can quote.
    Usage: @include('sections.magnoolia.answer-unit', ['unit' => $data])
    $unit keys: eyebrow, title, answer, facts (array), cta_label, cta_route, disclaimer
    ══════════════════════════════════════════════════════════════ --}}
@php
    $id = 'answer-unit-' . Str::slug($unit['title'] ?? 'block');
@endphp

<section class="mg-answer-unit section-space--sm" aria-labelledby="{{ $id }}"
         style="background:#f9f8f5;border-top:1px solid rgba(29,36,48,.07);border-bottom:1px solid rgba(29,36,48,.07);">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                {{-- Eyebrow --}}
                @if(!empty($unit['eyebrow']))
                <div class="mg-answer-unit__eyebrow"
                     style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
                            color:#c89443;margin-bottom:12px;">
                    {{ $unit['eyebrow'] }}
                </div>
                @endif

                {{-- Title --}}
                <h2 id="{{ $id }}" class="mg-answer-unit__title"
                    style="font-size:clamp(22px,3vw,30px);font-weight:700;color:#1d2430;
                           margin-bottom:16px;line-height:1.3;">
                    {{ $unit['title'] }}
                </h2>

                {{-- Answer paragraph --}}
                @if(!empty($unit['answer']))
                <p class="mg-answer-unit__answer"
                   style="font-size:16px;line-height:1.8;color:#444;margin-bottom:20px;">
                    {!! $unit['answer'] !!}
                </p>
                @endif

                {{-- Facts list --}}
                @if(!empty($unit['facts']))
                <ul class="mg-answer-unit__facts"
                    style="list-style:none;padding:0;margin:0 0 24px;display:flex;flex-wrap:wrap;gap:10px;"
                    aria-label="{{ __('magnoolia.answer_unit.facts_label', [], app()->getLocale()) }}">
                    @foreach($unit['facts'] as $fact)
                    <li style="display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:600;
                                color:#1d2430;background:#fff;border:1px solid rgba(200,148,67,.3);
                                border-radius:100px;padding:6px 16px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#c89443;flex-shrink:0;"></span>
                        {{ $fact }}
                    </li>
                    @endforeach
                </ul>
                @endif

                {{-- CTA --}}
                @if(!empty($unit['cta_label']) && !empty($unit['cta_route']))
                <div style="margin-bottom:12px;">
                    <a href="{{ $unit['cta_route'] }}" class="zoomvilla-btn"
                       data-event="cta_click" data-page="{{ $unit['cta_route'] }}">
                        {{ $unit['cta_label'] }} <i class="icon-angle-small-right"></i>
                    </a>
                </div>
                @endif

                {{-- Disclaimer --}}
                @if(!empty($unit['disclaimer']))
                <p class="mg-answer-unit__disclaimer"
                   style="font-size:12px;color:#9a9490;margin:0;line-height:1.6;">
                    {{ $unit['disclaimer'] }}
                </p>
                @endif

            </div>
        </div>
    </div>
</section>

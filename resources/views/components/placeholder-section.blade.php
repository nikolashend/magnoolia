{{--
    placeholder-section.blade.php

    Development scaffold used in Phase 1 homepage skeleton to mark future sections.
    Renders a clearly labelled dashed-border placeholder so the page structure
    is visible during development without any real content.

    To be replaced one-by-one as phases are implemented.

    Props:
    - $id (string) — optional HTML id for anchor linking
    - $label (string) — e.g. "PHASE 2", "PHASE 3"
    - $title (string) — section name / future heading
    - $phaseNote (string) — optional short note about what will go here
--}}
@props([
    'id'        => '',
    'label'     => 'TULEMAS',
    'title'     => 'Sektsioon',
    'phaseNote' => '',
])

<div
    class="placeholder-section"
    @if($id) id="{{ $id }}" @endif
    role="region"
    aria-label="{{ $title }} — {{ $label }}"
>
    <div class="placeholder-section__tag">{{ $label }}</div>
    <div class="placeholder-section__title">{{ $title }}</div>
    @if($phaseNote)
        <p class="placeholder-section__note">{{ $phaseNote }}</p>
    @endif
</div>

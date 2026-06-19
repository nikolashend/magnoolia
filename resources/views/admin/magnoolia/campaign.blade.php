@extends('admin.magnoolia._layout')

@section('title', 'Campaign')

@section('admin_content')
    @php
        $type = old('campaign_discount_type', $settings?->campaign_discount_type ?? 'text');
        $eur = $settings && $settings->campaign_discount_cents ? number_format($settings->campaign_discount_cents / 100, 2, '.', '') : '';
        $active = (bool) ($settings?->campaign_active);
    @endphp

    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">Campaign</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Set up a temporary promotion. <strong>Global campaign</strong> — when active it applies to all public eligible homes and shows on the homes page.
            Edits are <strong>draft only</strong>; they go live after you <a href="{{ route('admin.magnoolia.publish.form') }}">Publish</a>.
        </p>
    </div>

    {{-- Live preview --}}
    <div class="card" style="margin-bottom:14px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9a8b6f;margin-bottom:10px;">Preview (how it appears on the site)</div>
        @if($active && filled($settings?->campaign_note_et))
            <div style="background:#1d2430;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <span style="background:#c89443;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:.06em;">KAMPAANIA</span>
                <span style="color:#fff;font-size:14px;">
                    {{ $settings->campaign_note_et }}
                    @if($settings->campaign_discount_type === 'fixed' && $settings->campaign_discount_cents)
                        <strong style="color:#e8c98a;"> −{{ number_format($settings->campaign_discount_cents / 100, 0, '.', ' ') }} €</strong>
                    @endif
                </span>
                @if($settings->campaign_cta_label)
                    <span style="margin-left:auto;background:#c89443;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:700;">{{ $settings->campaign_cta_label }}</span>
                @endif
            </div>
            @if($settings->campaign_deadline)
                <div style="font-size:12px;color:#9a948a;margin-top:8px;">Until {{ $settings->campaign_deadline->format('d.m.Y') }} @if($settings->campaign_legal_note) · {{ $settings->campaign_legal_note }}@endif</div>
            @endif
        @else
            <div class="status status-warn">Campaign is inactive (or has no ET text) — nothing shows on the public site.</div>
        @endif
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.magnoolia.campaign.update') }}" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            @csrf
            <div>
                <label>Campaign active</label>
                <select name="campaign_active"><option value="0" @selected(!$active)>No — hidden</option><option value="1" @selected($active)>Yes — show on site</option></select>
            </div>
            <div>
                <label>Discount type</label>
                <select name="campaign_discount_type" onchange="document.getElementById('eurRow').style.display=this.value==='fixed'?'block':'none'">
                    <option value="text" @selected($type==='text')>Text-only campaign (no number)</option>
                    <option value="fixed" @selected($type==='fixed')>Fixed amount (€)</option>
                    <option value="none" @selected($type==='none')>No discount</option>
                </select>
            </div>
            <div id="eurRow" style="display:{{ $type==='fixed'?'block':'none' }};">
                <label>Discount amount (€)</label>
                <input type="number" step="0.01" min="0" name="campaign_discount_eur" value="{{ old('campaign_discount_eur', $eur) }}" placeholder="e.g. 5000">
                <small style="color:#9a948a;">Shown to buyers in euros. Stored internally in cents.</small>
            </div>
            <div>
                <label>Deadline</label>
                <input type="date" name="campaign_deadline" value="{{ old('campaign_deadline', optional($settings?->campaign_deadline)->toDateString()) }}">
            </div>
            <div><label>Campaign text — ET <span style="color:#b71c1c;">*</span> (required if active)</label><textarea name="campaign_note_et" rows="2">{{ old('campaign_note_et', $settings?->campaign_note_et) }}</textarea></div>
            <div><label>Campaign text — RU</label><textarea name="campaign_note_ru" rows="2">{{ old('campaign_note_ru', $settings?->campaign_note_ru) }}</textarea></div>
            <div><label>Campaign text — EN</label><textarea name="campaign_note_en" rows="2">{{ old('campaign_note_en', $settings?->campaign_note_en) }}</textarea></div>
            <div><label>Legal note</label><input type="text" name="campaign_legal_note" value="{{ old('campaign_legal_note', $settings?->campaign_legal_note) }}" placeholder="e.g. terms apply"></div>
            <div><label>CTA label</label><input type="text" name="campaign_cta_label" value="{{ old('campaign_cta_label', $settings?->campaign_cta_label) }}" placeholder="e.g. Küsi pakkumist"></div>
            <div><label>CTA target (URL or route)</label><input type="text" name="campaign_cta_target" value="{{ old('campaign_cta_target', $settings?->campaign_cta_target) }}" placeholder="/kontakt"></div>
            <div style="grid-column:1/-1;"><button type="submit">Save draft campaign</button></div>
        </form>
    </div>
@endsection

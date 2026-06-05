@extends('admin.magnoolia._layout')

@section('title', 'Campaign draft')

@section('admin_content')
    <div class="card">
        <form method="POST" action="{{ route('admin.magnoolia.campaign.update') }}" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            @csrf
            <div><label>Campaign active</label><select name="campaign_active"><option value="1" @selected($settings?->campaign_active)>Yes</option><option value="0" @selected(!$settings?->campaign_active)>No</option></select></div>
            <div><label>Discount cents</label><input type="number" name="campaign_discount_cents" value="{{ $settings?->campaign_discount_cents }}"></div>
            <div><label>Deadline</label><input type="date" name="campaign_deadline" value="{{ optional($settings?->campaign_deadline)->toDateString() }}"></div>
            <div><label>Legal note</label><input type="text" name="campaign_legal_note" value="{{ $settings?->campaign_legal_note }}"></div>
            <div><label>ET text</label><textarea name="campaign_note_et" rows="3">{{ $settings?->campaign_note_et }}</textarea></div>
            <div><label>RU text</label><textarea name="campaign_note_ru" rows="3">{{ $settings?->campaign_note_ru }}</textarea></div>
            <div style="grid-column:1/-1;"><label>EN text</label><textarea name="campaign_note_en" rows="3">{{ $settings?->campaign_note_en }}</textarea></div>
            <div style="grid-column:1/-1;"><button type="submit">Save draft campaign</button></div>
        </form>
    </div>
@endsection

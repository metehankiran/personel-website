@extends('layouts.app')

@section('title', 'CV — ' . $general->author_name)

@section('content')
    <section><div class="container-narrow">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:48px;flex-wrap:wrap;margin-bottom:56px;">
            <div>
                <div class="eyebrow">CV</div>
                <h1 class="h1-md">{{ $general->author_name }}</h1>
                <p style="margin:12px 0 0;font-size:18px;color:var(--dim);">{{ $general->author_title ?? 'Developer' }} · {{ $general->author_location ?? '' }}</p>
            </div>
            @if($cvPath)
                <a href="{{ Storage::url($cvPath) }}" class="btn btn-primary" download>↓ CV İndir</a>
            @endif
        </div>
        <div style="display:flex;flex-direction:column;gap:56px;">
            <div class="split-cv">
                <div class="eyebrow-sm">Özet</div>
                <p style="margin:0;font-size:16px;line-height:1.7;opacity:0.92;">{{ $general->bio ?? '' }}</p>
            </div>

            @if($experiences->isNotEmpty())
                <div class="split-cv">
                    <div class="eyebrow-sm">Tecrübe</div>
                    <div style="display:flex;flex-direction:column;gap:32px;">
                        @foreach($experiences as $experience)
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:baseline;gap:16px;margin-bottom:4px;">
                                    <h3 style="margin:0;font-size:17px;font-weight:600;">{{ $experience->title }}</h3>
                                    <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;white-space:nowrap;">{{ $experience->start_date->format('Y') }} — {{ $experience->end_date ? $experience->end_date->format('Y') : 'bugün' }}</span>
                                </div>
                                <div style="font-size:14px;color:var(--dim);margin-bottom:8px;">{{ $experience->company }}</div>
                                @if($experience->description)
                                    <p style="margin:0;font-size:14px;opacity:0.85;line-height:1.6;">{{ $experience->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($skills->isNotEmpty())
                <div class="split-cv">
                    <div class="eyebrow-sm">Yetkinlikler</div>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach($skills as $skill)
                            <div style="display:grid;grid-template-columns:100px 1fr;gap:16px;align-items:baseline;">
                                <span style="font-size:13px;color:var(--dim);">{{ $skill->name }}</span>
                                <span style="font-size:14px;font-weight:500;">{{ collect($skill->items)->pluck('name')->join(' · ') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($educations->isNotEmpty())
                <div class="split-cv">
                    <div class="eyebrow-sm">Eğitim</div>
                    <div style="display:flex;flex-direction:column;gap:24px;">
                        @foreach($educations as $education)
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                                    <h3 style="margin:0;font-size:16px;font-weight:600;">{{ $education->field }}, {{ $education->degree->getLabel() }}</h3>
                                    <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;">{{ $education->start_date->format('Y') }} — {{ $education->end_date ? $education->end_date->format('Y') : 'devam' }}</span>
                                </div>
                                <div style="font-size:14px;color:var(--dim);">{{ $education->school }}{{ $education->gpa ? ' · '.$education->gpa : '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($languages->isNotEmpty())
                <div class="split-cv">
                    <div class="eyebrow-sm">Diller</div>
                    <div style="font-size:14px;line-height:2;">
                        @foreach($languages as $language)
                            <div>{{ $language->name }} — {{ $language->level->getLabel() }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div></section>
@endsection

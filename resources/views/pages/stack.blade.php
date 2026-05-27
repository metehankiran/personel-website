@extends('layouts.app')

@section('title', 'Stack — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Stack</div>
        <h1 class="h1">Kullandığım teknolojiler.</h1>
        <p class="lede">Modaya kapılmadan, doğrulanmış araçlarla çalışıyorum. Her teknolojinin yanında o araçla geçirdiğim zamanın yansıması var.</p>
        <div style="margin-top:64px;">
            @foreach($skills as $skill)
                <div class="stack-section">
                    <div class="stack-aside">
                        <h2>{{ $skill->name }}</h2>
                        @if($skill->description)
                            <p>{{ $skill->description }}</p>
                        @endif
                    </div>
                    <div>
                        @foreach($skill->items as $item)
                            <div class="stack-row">
                                <span class="stack-name">{{ $item['name'] }}</span>
                                <span class="stack-desc">{{ $item['description'] }}</span>
                                <div class="stack-bar">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="stack-pip {{ $i <= $item['level'] ? 'on' : '' }}"></span>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div></section>
@endsection

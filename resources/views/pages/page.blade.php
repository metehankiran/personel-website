@extends('layouts.app')

@section('title', $page->title . ' — ' . $general->author_name)

@section('content')
    <section><div class="container-narrow">
        <div style="margin-bottom:48px;">
            <h1 class="h1">{{ $page->title }}</h1>
        </div>
        <div class="prose">
            {!! $page->body !!}
        </div>
    </div></section>
@endsection

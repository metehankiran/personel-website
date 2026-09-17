@extends('layouts.app')

@section('title', 'İletişim: Proje Teklifi ve Danışmanlık')
@section('meta_description', $general->author_name.' ile iletişime geçin: e-posta, telefon ve proje formu.'.(filled($general->availability_status) ? ' Durum: '.$general->availability_status.'.' : '').' Genelde 24 saat içinde dönüş.')

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::breadcrumbs(['İletişim' => \App\Support\Seo::route('contact')])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-start">

                {{-- Left Column — Contact Info --}}
                <div>
                    <x-eyebrow class="mb-3">İletişim</x-eyebrow>
                    <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Birlikte<br/>bir şey yapalım.</h1>
                    <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[480px]">Bir proje fikrin mi var? Sadece selam mı vereceksin? Her ikisi de iyi. Genelde 24 saat içinde dönüyorum.</p>

                    <div class="mt-12">
                        @if($general->author_email)
                            <x-contact-row key="email" label="Email" :value="$general->author_email" :href="'mailto:'.$general->author_email" icon="mail" />
                        @endif

                        @if($general->author_phone)
                            <x-contact-row key="phone" label="Telefon" :value="$general->author_phone" :href="'tel:'.preg_replace('/[^0-9+]/', '', $general->author_phone)" icon="phone" />
                        @endif

                        @foreach($social->profiles() as $platform => $profile)
                            <x-contact-row :key="$platform" :label="$profile['label']" :value="Str::of($profile['url'])->after('://')->rtrim('/')" :href="$profile['url']" :platform="$platform" external />
                        @endforeach

                        @php($addressLine = $general->author_address ?: $general->author_location)
                        @if($addressLine)
                            <x-contact-row key="address" label="Adres" :value="$addressLine" :href="$general->google_maps_url ?: null" icon="map-pin" external />
                        @endif
                    </div>
                </div>

                {{-- Right Column — Contact Form --}}
                <livewire:contact-form :service="request()->query('service')" />
            </div>
        </div>
    </section>
@endsection

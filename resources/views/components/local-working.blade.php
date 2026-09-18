@props(['provinces'])

{{-- How the work goes with a nearby business; shared by the service area list and the area pages. --}}
<div {{ $attributes }}>
    <x-section-heading class="mb-7">Yerelde nasıl çalışıyorum</x-section-heading>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
            <h3 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Yüz yüze görüşme</h3>
            <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">İşletmeyi yerinde görmek çoğu zaman bir saatlik toplantıdan fazlasını anlatır. {{ $provinces }} içinde ilk görüşmeyi yüz yüze yapabiliriz.</p>
        </div>
        <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
            <h3 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Uzaktan, aynı hızda</h3>
            <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">İlçeye gelmem gerekmeyen işlerde görüntülü görüşme ve canlı önizleme ile ilerliyoruz; mesafe süreci yavaşlatmıyor.</p>
        </div>
        <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
            <h3 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Ajans değil, işi yapan kişi</h3>
            <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">Aradığında karşına çıkan kişi, sitenin kodunu yazan kişi. Teslimden sonra da aynı numaradan ulaşırsın.</p>
        </div>
    </div>
</div>

<x-mail::message>
# Yeni iletişim mesajı

**{{ $contact->name }}** ({{ $contact->email }}) siteden bir mesaj gönderdi.

<x-mail::panel>
**Konu:** {{ $contact->subject }}
@if($contact->phone)

**Telefon:** {{ $contact->phone }}
@endif

**Tarih:** {{ $contact->created_at->translatedFormat('d F Y H:i') }}
</x-mail::panel>

{{ $contact->message }}

<x-mail::button :url="$adminUrl">
Admin panelinde aç
</x-mail::button>

Bu e-postaya doğrudan yanıt verirsen mesaj {{ $contact->name }} adlı kişiye gider.

{{ config('app.name') }}
</x-mail::message>

@component('mail::message')

<div class="primary-text">
    Mamy nadzięję, że nagranie od <b>{{ $influencerNick }}</b> spełniło Twoje oczekiwania. Podziel się proszę swoją opinią na ten temat.
</div>

<div class="primary-text" style="margin-top: 20px;">
    @component('mail::button', ['url' => $url])
        Kliknij, aby ocenić zamówienie
    @endcomponent
</div>

@endcomponent

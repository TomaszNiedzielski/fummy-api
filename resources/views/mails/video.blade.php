@component('mail::message')

<div class="primary-text">
    Hurra! <span class="primary-color">{{ $nick }}</span> nagrał(a) dla Ciebie zamówione video.
    Możesz je zobaczyć lub pobrać klikając przycisk poniżej.
</div>

@component('mail::button', ['url' => $url])
Zobacz video
@endcomponent

<div class="primary-text" style="font-size: 13px;">
    Jeżeli video nie zostało nagrane według twoich instrukcji lub jest całkowicie niezgodne z twoim zamówieniem, możesz skontaktować się z nami
    pod tym adresem e-mail. Po otrzymaniu twojego zgłoszenia dołożymy wszelkich starań, aby Ci pomóc.
</div>

@endcomponent

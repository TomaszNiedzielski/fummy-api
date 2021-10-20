@component('mail::message')

<div class="primary-text">Otrzymaliśmy prośbę o zmianę hasła do twojego konta.</div>

<div class="primary-text" style="margin-top: 20px;">Kliknij przycisk poniżej, aby zresetować hasło.</div>

@component('mail::button', ['url' => $url])
Zresetuj hasło
@endcomponent

<div class="primary-text" style="font-size: 13px;">Jeśli nie chcesz resetować swojego hasła, zignoruj tę wiadomość. Hasło pozostanie niezmienione.</div>

@endcomponent

@component('mail::message')
## Reset hasła do konta

Otrzymaliśmy prośbę o zmianę hasła do twojego konta.
Kliknij przycisk poniżej, aby zresetować hasło.

@component('mail::button', ['url' => $url])
Resetuj
@endcomponent

Jeśli nie chcesz resetować swojego hasła, zignoruj tę wiadomość. Hasło pozostanie niezmienione.

Z poważaniem,<br>
{{ config('app.name') }}
@endcomponent

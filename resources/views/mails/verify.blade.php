@component('mail::message')

## Witaj {{ $userName }},

dziękujemy za utworzenie konta w serwisie {{ config('app.name') }}

Potwierdź swój adres e-mail, klikając przycisk poniżej.

@component('mail::button', ['url' => $url])
Potwierdź
@endcomponent

Z poważaniem,<br>
{{ config('app.name') }}
@endcomponent

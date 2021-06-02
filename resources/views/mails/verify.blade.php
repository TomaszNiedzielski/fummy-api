@component('mail::message')

## Witaj {{ $userName }},

dziękujemy za utworzenie konta w serwisie FameChallenge

Potwierdź swój adres e-mail, klikając przycisk poniżej.

@component('mail::button', ['url' => $url])
Potwierdź
@endcomponent

Z poważaniem,<br>
{{ config('app.name') }}
@endcomponent

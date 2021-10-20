@component('mail::message')

<div class="primary-text" style="font-size: 18px; margin-bottom: 10px; font-weight: bold!important;">Witaj, {{ $userName }}!</div>

<div class="primary-text">
    Dziękujemy za utworzenie konta w serwisie <b class="primary-color">{{ config('app.name') }}</b>.
    Potwierdź swój adres e-mail, abyśmy wiedzieli, że napewno należy do Ciebie.
    Będziesz otrzymywał(a) tu powiadomienia o zamówieniach od Twoich fanów.
</div>

@component('mail::button', ['url' => $url])
Zweryfikuj adres email
@endcomponent

<div class="primary-text">Masz do nas jakieś pytania? Możesz kontaktować się z nami pod tym adresem e-mail.</div>

<div class="primary-text" style="margin-top: 20px;">Dzięki,</div>

<div class="primary-text" style="margin-top: 20px;">Fummy</div>

@endcomponent

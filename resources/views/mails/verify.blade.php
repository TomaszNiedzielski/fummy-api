@component('mail::message')

# Witaj, {{ $userName }}!

### Dziękujemy za utworzenie konta i dołączenie do rodziny <span style="color: #df2674;">{{ config('app.name') }}</span>.

## Potwierdź swój adres e-mail,
### klikając przycisk poniżej.

<div style="display: flex; justify-content: center">
    <img alt="finger" src="/icons/hand.png" height=80px />
</div>

@component('mail::button', ['url' => $url])
POTWIERDŹ
@endcomponent

@endcomponent

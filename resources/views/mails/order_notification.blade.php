@component('mail::message')
# Czeka na Ciebie nowe zlecenie.

### Kliknij link poniżej i sprawdź swoje zamówienia.

@component('mail::button', ['url' => $url])
Idź do zamówienia
@endcomponent

@endcomponent

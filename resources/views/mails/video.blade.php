@component('mail::message')

### Hurra! {{ $nick }} nagrał(a) dla Ciebie zamówione video.

@component('mail::button', ['url' => $url])
Zobacz
@endcomponent

@endcomponent

@component('mail::message')

## O to video nagrane specjalnie dla Ciebie.

@component('mail::button', ['url' => $url])
Zobacz
@endcomponent

Z poważaniem,<br>
{{ config('app.name') }}
@endcomponent

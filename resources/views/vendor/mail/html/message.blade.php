@component('mail::layout')

<div style="display: flex; justify-content: center; align-items: center; margin-bottom: 30px;">
    <img alt="logo" src="/icons/logo.png" height="36px" />
    <h1 style="margin: 0 0 0 5px; font-family: 'futura-extra-bold-oblique';">Fummy</h1>
</div>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent

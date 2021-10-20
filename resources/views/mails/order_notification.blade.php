@component('mail::message')

<div class="primary-text">
    Jeden z twoich fanów poprosił Cię o nagranie dla niego video. Masz aż 7 dni, aby zrealizować to zamówienie.
    Nagraj video według podanych przez niego instrukcji.
</div>

<div class="primary-text" style="margin-top: 20px;">Pamiętaj, że video powinno być w orientacji pionowej i nie dłuższe niż jedna minuta.</div>

<div class="primary-text" style="margin-top: 20px;">Kliknij przycisk poniżej, aby poznać szczegóły zamówienia.</div>

@component('mail::button', ['url' => $url])
Idź do zamówienia
@endcomponent

@endcomponent

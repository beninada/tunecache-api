@component('mail::message')
# Welcome to TuneCache

Thank you for signing up.

@component('mail::button', ['url' => config('app.url')])
Go to TuneCache
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

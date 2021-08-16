@component('mail::message')
# Dear, {{$content['name']}}

{{$content['body']}}

Thanks,
{{ config('app.name') }}
@endcomponent
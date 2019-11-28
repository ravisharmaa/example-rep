@component('mail::message')

    Dear {{$user->department->head}}, <br>

{{$user->email}} has returned item on {{$subscription->returned_at->diffForHumans()}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent

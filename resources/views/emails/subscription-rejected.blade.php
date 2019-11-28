@component('mail::message')

    Dear {{$subscription->user->name}}, <br>

Your request for {{$subscription->item_name}} has been rejected by {{$subscription->user->department->head}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent

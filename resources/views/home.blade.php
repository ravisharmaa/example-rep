@extends('layouts.app')

@section('content')
    @can('create-department')
        <daily-records-component :attendances="{{$attendances}}"></daily-records-component>
    @else
        <device-list-component :subscriptions="{{$subscriptions}}"></device-list-component>
    @endcan
@endsection

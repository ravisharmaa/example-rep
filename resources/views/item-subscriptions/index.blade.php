@extends('layouts.app')

@section('content')
    <subscriptions-list-component :subscriptions="{{$subscriptions}}"></subscriptions-list-component>
@endsection

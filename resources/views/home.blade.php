@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row-fluid justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Assigned Devices</div>

                    <div class="card-body">
                        <table class="table-responsive">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Code</th>
                                <th scope="col">Tag</th>
                                <th scope="col">Vendor</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($devices as $device)
                            <tr>
                                <th scope="row"></th>
                                <td>{{$device->name}}</td>
                                <td>{{$device->code}}</td>
                                <td>{{$device->tag}}</td>
                                <td>{{$device->vendor}}</td>
                                <td>
                                    <form action="{{route('subscriptions.store', ['device' => $device])}}" method="POST">
                                        {{csrf_field()}}
                                        <input type="submit" class="btn btn-sm btn-outline-primary" value="Request Access">
                                    </form>
                                </td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

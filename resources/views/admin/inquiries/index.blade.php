@extends('layouts.auth')

@section('content')
<div class="content-wrapper" style="min-height: 640px">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> Inquiries
      </h3>
    </div>

    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> From </th>
                            <th> Email </th>
                            <th> Message </th>
                            <th> Inquired On </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inquiries as $inquiry)
                            <tr>
                                <td style="max-width:100px" class="truncate-text"> {{ $inquiry['name'] }} </td>
                                <td style="max-width:150px" class="truncate-text"> {{ $inquiry['email'] }} </td>
                                <td style="max-width:150px" class="truncate-text"> {{ $inquiry['message'] }} </td>
                                <td style="max-width:150px" class="truncate-text"> {{ $inquiry['created_at'] }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

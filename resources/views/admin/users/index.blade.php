@extends('layouts.auth')

@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-home"></i>
      </span> Users
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span><a href="{{url('/admin/add-user')}}" style="text-decoration: none"> <i class="mdi mdi-plus icon-sm text-primary align-middle"></i> </a>
        </li>
      </ul>
    </nav>
  </div>

  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @if(session('message'))
          <div class="alert alert-success">
            {{ session('message') }}
          </div>
          @endif
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th> Name </th>
                  <th> Email </th>
                  <th class="text-center"> Role </th>
                  <th class="text-end"> Created By </th>
                  @if (Auth::user()->role->name === 'admin')
                    <th class="text-center"> Action </th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td class="truncate-text"> {{ $user['name'] }}</a>
                    </td>
                    <td> {{ $user['email'] }} </td>
                    <td class="text-center"> {{ ucfirst($user['role']->name) }} </td>
                    <td class="text-end truncate-text"> {{ \Carbon\Carbon::parse($user['updated_at'])->format('F j, Y g:i A') }} </td>
                    @if (Auth::user()->role->name === 'admin')
                        <td class="text-center">
                            <a href="{{url('/admin/user')}}/{{ $user['id'] }}"><i class="mdi mdi-pencil"></i></a>
                        </td>
                    @endif
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

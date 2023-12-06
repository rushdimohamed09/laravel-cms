@extends('layouts.auth')

@section('content')

@if(isset($user))
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> Update User
      </h3>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <span></span><a href="{{url('/admin/users')}}" style="text-decoration: none"> View Users <i class="mdi mdi-eye icon-sm text-primary align-middle"></i> </a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="row" id="section-template">
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
            @if (session('duplicate'))
            <div class="alert alert-danger">
                {{ session('duplicate') }}
            </div>
            @endif
            @if (session('no-change'))
            <div class="alert alert-secondary">
                {{ session('no-change') }}
            </div>
            @endif
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif
            <form method="POST" action="{{ url('/user-profile')}}/{{ $user->id }}" class="forms-sample">
              @method('PUT')
              @csrf
              <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter new user's full name" value="{{ $user->name }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Enter new user's Email" value="{{ $user->email }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="role" class="col-sm-3 col-form-label">Role</label>
                <div class="col-sm-9">
                  <select class="form-control" name="role" id="role" style="padding: 0.94rem 1.375rem; -webkit-appearance:auto">
                    {{$user->role->name}}
                    @foreach($permitRoles as $role)
                      <option value="{{ $role['name'] }}" @if($role['name'] == $user->role->name) selected @endif>
                        {{ ucfirst($role['name']) }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-gradient-primary mr-2">Update User</button>
              <button class="btn btn-light">Clear</button>
            </form>

          </div>
        </div>
      </div>

      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            @if (session('password-error'))
            <div class="alert alert-danger">
                {{ session('password-error') }}
            </div>
            @endif
            @if (session('password-message'))
            <div class="alert alert-success">
                {{ session('password-message') }}
            </div>
            @endif
            <form method="POST" action="{{ url('/user-password') }}/{{ $user->id }}" class="forms-sample">
              @method('PUT')
              @csrf
              @if(Auth::user()->id == $user->id)
              <div class="form-group row">
                <label for="current_password" class="col-sm-3 col-form-label">Current Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter a Password">
                </div>
              </div>
              @endif
              <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label">New Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Enter a Password">
                </div>
              </div>
              <div class="form-group row">
                <label for="password_confirmation" class="col-sm-3 col-form-label">Re Password</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                </div>
              </div>
              <button type="submit" class="btn btn-gradient-primary mr-2">Update Password</button>
              <button class="btn btn-light">Clear</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@else
<div class="text-center" style="padding-top: 100px">
    <p><i class="mdi mdi-alert-circle-outline text-primary align-middle"></i> Section Not found</p>
    <p>
      <a href="{{url('admin/add-user')}}" class="btn btn-success">Create New User</a>
      <a href="{{url('admin/users')}}" class="btn btn-primary">View Users</a>
    </p>
</div>
@endif
@endsection

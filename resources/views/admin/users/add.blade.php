@extends('layouts.auth')

@section('content')

<form method="POST" action="{{ url('/user') }}" id="myForm">
  @csrf
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
          <i class="mdi mdi-home"></i>
        </span> Add New User
      </h3>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <span></span><a href="{{url('/admin/users')}}" style="text-decoration: none"> View Users <i class="mdi mdi-eye icon-sm text-primary align-middle"></i> </a>
          </li>
        </ul>
      </nav>
    </div>

    @if (session('duplicate'))
    <div class="alert alert-danger">
        {{ session('duplicate') }}
    </div>
    @endif

    <div class="row" id="section-template">
      <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">

              <form class="forms-sample">
                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter new user's full name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-sm-3 col-form-label">Email</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter new user's Email">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="role" class="col-sm-3 col-form-label">Role</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="role" id="role" style="padding: 0.94rem 1.375rem; -webkit-appearance:auto">
                        @foreach($permitRoles as $role)
                            <option value="{{ $role['name'] }}" @if($loop->first) selected @endif>
                                {{ ucfirst($role['name']) }}
                            </option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="password" class="col-sm-3 col-form-label">Password</label>
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
                <button type="submit" class="btn btn-gradient-primary mr-2">Create new User</button>
                <button class="btn btn-light">Clear</button>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</form>

@endsection

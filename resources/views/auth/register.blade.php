@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Sumanas</b>Tech.</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="input-group mb-3">
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
              @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
        </div>
      </form>
      <div class="text-center mt-2">
        <a href="{{route('login')}}" class="text-center">I already have a membership</a>
      </div>
    </div>
  </div>
</div>
@endsection
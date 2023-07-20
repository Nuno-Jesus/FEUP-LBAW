@extends('layouts.app')

@section('content')
  <section class="d-flex justify-content-center m-5">
    <div class="card w-50 d-flex flex-column align-items-center">
      <h3 class = "m-4">Join us!</h3>
      <form class = "d-flex flex-column w-75" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}
        
        <div class="form-group m-2 d-flex flex-column">
          <label class = "mb-2" for="name">
            <span>Username</span>
            <small class = "required-input">*</small>
          </label>
          <input class = "form-control" id="name" type="text" name="name" required>
          @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
          @endif
        </div>    
            
        <div class="form-group m-2 d-flex flex-column">
          <label class = "mb-2" for="email">
            <span>Email</span>
            <small class = "required-input">*</small>
          </label>
          <input class = "form-control" id="email" type="email" name="email" required>
            @if ($errors->has('email'))
              <span class="error">
                  {{ $errors->first('email') }}
              </span>
            @endif  
        </div>
        
        <div class="form-group d-flex justify-content-between">
          <div class="form-group m-2 d-flex flex-column w-100">
            <label class = "mb-2" for="password">
              <span>Password</span>
              <small class = "required-input">*</small>
            </label>
            <input class = "form-control" id="password" type="password" name="password" required>
              @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
              @endif  
          </div>

          <div class="form-group m-2 d-flex flex-column w-100">
            <label class = "mb-2" for="password-confirm">
              <span>Confirm Password</span>
              <small class = "required-input">*</small>
            </label>
            <input class = "form-control" id="password-confirm" type="password" name="password_confirmation" required>
          </div>
        </div>

        <div class="form-group m-2 d-flex flex-column">
          <button class = "btn btn-primary" type="submit">Register</button>
        </div>
      </form>
    </div>
  </section>
@endsection

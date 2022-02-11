@extends('layouts.app')

@section('content')
  <section class="d-flex justify-content-center m-5" id = "content">
    <div class="card w-50 d-flex flex-column align-items-center">
        <h3 class = "m-3">Password Recovery</h3>

        <form class = "card-body w-75" id = "login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group d-flex flex-column mt-3">
                <label for="email">
                    <h6>Email</h6>
                </label>
                <input class = "form-control" type = "email" id="email" name="email" value = "{{old('email')}}" required autofocus>
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
            <button class="btn btn-primary w-100 mt-3" type="submit" >Send Email</button>
        </form>
    </div>
  </section>
@endsection
@extends('layouts.app')

@section('content')
  <section class="m-1 mt-3 w-100 container-fluid align-items-center">
    <span id = "userId" hidden>{{$user->id}}</span>
    <div class="row row-columns-2 justify-content-between">
      <!-- Side panel -->
      <div class="col-lg-3">
        @if(!Auth::user()->isadmin)
          @include('partials.profile.sidebar') 
    
        @else
          @include('partials.admin.sidebar')
    
        @endif
      </div>

      <div class="col-lg-9">
        <!-- User Data -->
        @include($content, ['user' => $user, 'entries' => $entries, 'errors' => $errors])
      </div>
      
    </div>
  </section>
@endsection
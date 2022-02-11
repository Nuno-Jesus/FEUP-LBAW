@extends('layouts.app')

@section('content')
  <div class = "d-flex flex-column">
    @include('partials.home.slider')

    <div class = "container-fluid mt-5" id="homeProductGrid">
      <div class="row m-5 mt-3">
        <h5>Products in other users carts:</h5>
        <div class="container-fluid">
          <div class="row">
            @foreach($productsList1 as $product)
              <div class="col-lg-3 mt-3">
                @include('partials.product.card', ['product' => $product])
              </div>
            @endforeach
          </div>
        </div>
      </div>  
  
      <div class="row m-5 mb-5">
        <h5>Products you might like:</h5>
        <div class="container-fluid">
          <div class="row">
            @foreach($productsList2 as $product)
              <div class="col-lg-3 mt-3">
                @include('partials.product.card', ['product' => $product])
              </div>
            @endforeach
          </div>
        </div>
      </div>  
    </div>
@endsection
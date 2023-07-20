@extends('layouts.app')

@section('content')
  <div class = "container-fluid mt-2">
    <div class="row m-5 mt-1">
      <div class="card col-md m-1">
        <div class="card-body d-flex justify-content-center">
          <img src = {{asset($product->image)}} width = "70%">
        </div>
      </div>

      <div class="col-md product-info d-flex flex-column">
        <h4>{{$product->name}}</h4>
        <span class = "mt-2 mb-2">{{$product->description}}</span>
        
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="mt-2 price">{{$product->price}}€</h3>

          @if($product->stock == 0)
          <h4 class = "text-danger mb-0">
              <i class = "fa fa-times"></i> Out of Stock
            </h4>
          
          @elseif($product->stock > 0 && $product->stock <= 5)
            <h4 class = "text-warning mb-0">
              <i class = "fa fa-check"></i> Low Stock
            </h4>
          @elseif($product->stock > 5)
            <h4 class = "text-success mb-0">
              <i class = "fa fa-check"></i> In Stock
            </h4>
          
            
          @endif

          <div class="w-50 d-flex justify-content-end">
            @if(Auth::guest() || (Auth::check() && !Auth::user()->isadmin && $product->stock > 0))
              @include('partials.product.add_to_cart_button', ['sentence' => 'Add to Cart', 'product' => $product])
            @endif

            @if(Auth::guest() || (Auth::check() && !Auth::user()->isadmin))
              @include('partials.product.add_to_wishlist_button', ['sentence' => 'Add to Wishlist', 'product' => $product])
            @endif

            @if(Auth::check() && Auth::user()->isadmin)
              @include('partials.product.edit_product_button', ['sentence' => 'Edit Product', 'product' => $product])
            @endif
          </div>
        </div>

        @include('partials.product.details', ['product' => $product])
      </div>
    </div>

    <div class="row m-5">
      <h4>Reviews</h4>
      <hr>
      
      @if(!Auth::guest())
        @if(!Auth::user()->isadmin)
          @include('partials.product.review_form', ['user' => $user, 'product' => $product])
        @endif
      @endif

      {{-- @php(dd($reviews)) --}}
      @foreach($reviews as $review)
        @include('partials.product.review', ['entry' => $review])
      @endforeach
    </div>
  </div> 
@endsection
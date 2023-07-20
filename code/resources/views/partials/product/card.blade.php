<div class="card m-1 h-100">
  
  <div class="card-body d-flex flex-column justify-content-between">
    <a href = {{url('products/'.$product->id)}}>
      <img src = {{asset($product->image)}} width = "100%" alt = "{{$product->name}}">
    </a>

    <div class="product-info d-flex flex-column">
      <strong>{{$product->name}}</strong>
      <small>{{$product->description}}</small>

      <div class="d-flex justify-content-between align-items-center">
        <h3 class="mt-2 price">{{$product->price}}€</h3>
        
        <div class="d-flex justify-content-between align-items-center">
          {{-- If the user's unauthenticaded or is not an admin (with valid stock) --}}
          @if(Auth::guest() || (Auth::check() && !Auth::user()->isadmin && $product->stock > 0))
            @include('partials.product.add_to_cart_button', ['sentence' => '', 'product' => $product])
          @endif

          @if(Auth::guest() || (Auth::check() && !Auth::user()->isadmin))
            @include('partials.product.add_to_wishlist_button', ['sentence' => '', 'product' => $product])
          @endif

          @if(Auth::check() && Auth::user()->isadmin)
            @include('partials.product.edit_product_button', ['sentence' => '', 'product' => $product])
          @endif
        </div>
      </div>
    </div>
  </div>
</div>          
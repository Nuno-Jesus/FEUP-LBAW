@extends('layouts.app')

@section('content')
  <!-- Products Grid -->
  <div class="container-fluid mb-5">
    @if(count($products) > 0)
    <div class="row">

      <div class="col-6 h-75">
        <p>Your search retrieved <strong>{{count($products)}} products</strong>.</p>
      </div>
    </div>

    <div id = "filterRow" class="row">
      <div class="col card">
        <form class = "row card-body d-flex align-items-center justify-content-between" 
        method = "POST" action="{{route('filter')}}" id = "filter">
          @csrf

          <div class="col-md d-flex justify-content-center">
              <label for="category" class="form-label">
              </label>
              <select name="orderBy" id="orderBy">
                <option value="none" selected> None </option>
                <option value="price-high-low">Price: high to low</option>
                <option value="price-low-high">Price: low to high</option>
                <option value="rating-low-high">Rating: low to high</option>
                <option value="rating-high-low">Rating: high to low</option>
                <option value="stock-low-high">Stock: low to high</option>
                <option value="stock-high-low">Stock: high to low</option>
              </select>
          </div>

          <div class="col-md d-flex justify-content-center">
            <input type="checkbox" class="btn-check" name = "stock" id="stock" checked>
            <label class="btn btn-outline-primary" for="stock"> In Stock</label>
          </div>
          
          <div class="col-md form-group">
            <label for="price" class="form-label">Max Price</label>
            <input type="range" name = "price" class="form-range" min="0" max="10000" step="1" id="price" value = 100>
            <span id = "priceSpan">100€</span>
          </div>

          <div class="col-md form-group">
            <label for="rating" class="form-label">Min Rating</label>
            <input type="range" name = "rating" class="form-range" min="0" max="5" step="1" id="rating" value="0">
            <span id = "ratingSpan">0</span>
          </div>

          <div class="col-md d-flex justify-content-center">
            <button id="filterButton" class = "btn btn-success">
              Filter
            </button>
          </div>
        </form>
      </div>
    </div>
      @php($i = 0)
      <div class="row row-columns-4">
        @foreach($products as $product)
          <div class="col-lg-3 mt-3">
            @include('partials.product.card', ['product' => $product])
          </div>
          @php($i++)

          <!-- Makes rows of 4 products each -->
          @if($i % 4 == 0)
            </div>
            <div class="row row-columns-4">
          @endif
        @endforeach  

        @for($i = 0; $i < 4 - count($products) % 4; $i++)
          <div class="col-lg-3 mt-3"></div>
        @endfor
      </div>
    
    @else
      @include('partials.product.not_found')
    @endif
  </div>

@endsection
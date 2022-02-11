<div class="w-100">
  <div class="d-flex justify-content-between mb-2">
    <h4>List of products in the store</h4>

    <form class = "w-50 m-1" method = "POST" action = "{{route('adminSearchProducts')}}">
      @csrf
      
      <input class="form-control me-2" type="search" name = "search" placeholder="Search">
    </form>

    @include('partials.buttons.add_button', 
      ['text' => 'New Product', 'id' => 'newProductButton', 'link' => route('newProduct')])
  </div>

  @php($i = 0)
  <div class="row row-columns-3">
    @foreach($entries as $entry)
      <div class="col-md">
        @include('partials.admin.product_entry', ['entry' => $entry])
      </div>

      @php($i++)
      <!-- Makes columns of 2 -->
      @if($i % 3 == 0)
        </div>
        <div class="row row-columns-3">
      @endif
    @endforeach  

    @for($i = 0; $i < 3 - count($entries) % 3; $i++)
      <div class="col-md"></div>
    @endfor
  </div>
</div>
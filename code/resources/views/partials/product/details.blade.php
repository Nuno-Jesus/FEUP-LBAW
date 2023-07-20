<div class="row m-5">
  <table class = "table">
    <thead>
      <tr>
        <th scope = "col">Details</th>
        <th scope = "col">Values</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>Category</td>
          <td><u><a class = "text-dark" href="/products/categories/{{$product->category}}">{{$product->category}}</a></u></td>
      </tr>
      
      <tr>
        <td>Brand</td>
        <td><u><a class = "text-dark" href="/products/search/brand/{{$product->brand}}">{{$product->brand}}</a></u></td>
      </tr>
      

      @if(!is_null($product->size))
        <tr>
          <td>Size</td>
          <td>{{$product->size}}</td>
        </tr>   
      @endif  

      <tr>
        <td>Rating</td>
        <td>{{$product->rating}} / 5.0</td>
      </tr>  
    </tbody>
  </table>
</div>
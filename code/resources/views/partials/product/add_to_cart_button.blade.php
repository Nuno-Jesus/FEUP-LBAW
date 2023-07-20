<form class = "m-1" method = "POST" action = {{url('/users/cart/'.$product->id)}}>
  @csrf
  @method('PUT')

  <button class="btn btn-outline-primary" type = "submit">
    <i class="fa fa-cart-plus"></i>
    <span>{{$sentence}}</span>
  </button>
</form>

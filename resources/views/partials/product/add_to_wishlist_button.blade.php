<form class = "m-1" method = "POST" action = {{url('/users/wishlist/product/'.$product->id)}}>
  @csrf
  @method('PUT')

  <button class="btn btn-outline-danger" type = "submit">
    <i class="fa fa-heart"></i>
    <span>{{$sentence}}</span>
  </button>
</form>
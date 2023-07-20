<div class="card mb-4">
  <div class="card-body d-flex justify-content-between">
    <a href={{url('products/'.$entry->id)}}>
      <img src={{asset($entry->image)}} alt={{$entry->name}} height = 100>
    </a>
    <div class="d-flex flex-column justify-content-between w-100">
      <span class = "card-title">{{$entry->name}}</span>
      <div class="d-flex justify-content-between">
        <h5 class = "price">{{$entry->price}}€</h5>
        <form method = "POST" action={{url('users/wishlist/product/'.$entry->id)}}>
          @csrf
          @method('delete')

          <button class = "btn btn-outline-danger" type = "submit">
            <i class="fa fa-times"></i>
            <span>Remove</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4" id = "productCard">
  <div class="card-body d-flex justify-content-between align-items-center">
    <a href = {{url('products/'.$entry->id)}}>
      <img src={{asset($entry->image)}} alt={{$entry->name}} height = 100>
    </a>
    
    <div class="w-100">
      <div class="d-flex flex-column">
        <span class = "card-title">{{$entry->name}}</span>
        <span>Stock: {{$entry->stock}}</span>
      </div>

      <div class="d-flex justify-content-between align-items-center">
        <h5 class = "price">{{$entry->price}}€</h5>
        <div class = "d-flex">
          <form class = "m-1" method = "POST" action={{url('admin/products/delete/'.$entry->id)}}>
            @csrf
            @method('delete')
  
            <button class = "btn btn-outline-danger" type = "submit">
              <i class="fa fa-times"></i>
            </button>
          </form>

          <a class = "m-1" href="{{url('admin/products/edit/'.$entry->id)}}">
            <button class = "btn btn-outline-primary" type = "submit">
              <i class="fa fa-pencil-square-o"></i>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
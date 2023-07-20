<div class="card m4-2 mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <h6>
        <strong>Order #{{$entry['order']->id}}</strong>
      </h6> 

      <span>
        {{$entry['order']->orderdate}} -> {{$entry['order']->orderdate}}
      </span>
    </div>

    <div class="d-flex flex-column mt-2">
      <span>
        {{
          $entry['address']->streetname.' '
          .$entry['address']->streetnumber.' '
          .$entry['address']->aptnumber.' '
          .$entry['address']->floor
        }}
      </span>

      <span>
        {{$entry['address']->zipcode}}
      </span>
    </div>
    
    <div class="d-flex flex-column mt-2">
      <h6>
        <strong>Items</strong>
      </h6> 

      <ul>
        @foreach($entry['products'] as $product)
          <li>
            <a class = "text-dark" href={{url('products/'.$product->id)}}>
              {{$product->name}} - <strong class = "text-primary">{{$product->price}}€</strong>
            </a>
          </li>
        @endforeach
      </ul>
      
    </div>
    
    <div class="d-flex justify-content-between mt-1 align-items-center">
      <h5 class = "price">{{$entry['total']}}€</h5>

      @if($entry['order']->orderstatus == 'Processing')
        <form class = "m-1" method = "POST" action={{url('users/orders/cancel/'.$entry['order']->id)}}>
          @csrf
          <button class = "btn btn-outline-danger w-40" type = "submit">
            <i class="fa fa-ban" aria-hidden="true"></i>
            Cancel order
          </button>
        </form>
      @endif
      
      @if($entry['order']->orderstatus == 'Processing')
        <span class = "text-secondary">
          <i class="fa fa-ellipsis-h"></i>
      
      @elseif($entry['order']->orderstatus == 'Accepted')
        <span class = "text-teal-500">
          <i class="fa fa-check"></i>

      @elseif($entry['order']->orderstatus == 'Packed')
        <span class = "text-orange-500">
          <i class="fa fa-gift"></i>  

      @elseif($entry['order']->orderstatus == 'Shipped')
        <span class = "text-info">
          <i class="fa fa-ship"></i>  
      
      @elseif($entry['order']->orderstatus == 'Cancelled by Store')
        <span class = "text-red-500">
          <i class="fa fa-ban"></i> 
      
      @elseif($entry['order']->orderstatus == 'Cancelled by Customer')
        <span class = "text-red-500">
          <i class="fa fa-ban"></i>
          
      @else
        <span class = "text-teal-500">
          <i class="fa fa-truck"></i>    

      @endif
      {{$entry['order']->orderstatus}}
      </span>
    </div>
  </div>
</div>
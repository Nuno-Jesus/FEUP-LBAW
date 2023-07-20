<div class="card m4-2 mb-4">
  <div class="card-body">
    <div class="d-flex">
      <h6>
        <strong>Order #{{$entry['order']->id}}</strong>
      </h6> 
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

    <form class = "d-flex" action={{url('admin/orders/edit/'.$entry['order']->id)}} method = "POST">
      @csrf
      <select class = "m-1" name = "orderStatus">
        <option value="Processing">Processing</option>
        <option value="Accepted">Accepted</option>
        <option value="Packed">Packed</option>
        <option value="Shipped">Shipped</option>
        <option value="Cancelled by Store">Cancelled</option>
      </select>
      <button class = "btn btn-success m-1">
        <i class="fa fa-check"></i>
      </button>
    </form>

    <div class="d-flex justify-content-between mt-1 align-items-center">
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
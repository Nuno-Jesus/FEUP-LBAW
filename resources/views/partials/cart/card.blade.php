<div class="card w-25 h-25">
  <div class="card-body d-flex justify-content-between">
    <div class="d-flex flex-column">
      <h5 class="card-subtitle m-1 text-muted">Sub Total:</h5>
      <h5 class="card-subtitle m-1 text-muted">Taxes:</h5>
      <h5 class="card-title m-1">Grand Total:</h5>
    </div>
    <div class = "d-flex flex-column">
      @php($taxes = 0.00)
      <h5 class="card-subtitle m-1 text-muted">{{$total}}€</h5>
      <h5 class="card-subtitle m-1 text-muted">{{$taxes}}€</h5>
      <h5 class="card-title m-1">{{$total + $taxes}}€</h5>
    </div>
  </div>

  <div class="d-flex justify-content-around">
    <div class="btn btn-success m-2 w-100">
      <a href="{{route('checkout')}}">Checkout</a>
    </div>

    <form class = "m-2 w-100" method = "POST" action = "{{route('emptyCart')}}">
      @csrf
      @method('delete')
      <button class="btn btn-outline-danger w-100" type = "submit">
        Empty Cart
      </button>
    </form>
  </div>
</div>
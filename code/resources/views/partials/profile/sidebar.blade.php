<div class="w-100 mb-5">
  <h4 class = "mb-4">Account Panel</h4>
  
  <hr class = "w-100 mb-3">

  <ul class="list-group list-group-numbered">
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="d-flex flex-column ms-2 me-auto">
        <a class="fw-bold" href = {{route('profile')}}>Profile</a>
        <small>Edit your profile</small>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="d-flex flex-column ms-2 me-auto">
        <a class="fw-bold" href = {{route('orders')}}>Orders</a>
        <small>Visit your order history</small>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="d-flex flex-column ms-2 me-auto">
        <a class="fw-bold" href = {{route('addresses')}}>Addresses</a>
        <small>Edit your addresses</small>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="d-flex flex-column ms-2 me-auto">
        <a class="fw-bold" href ={{route('wishlist')}}>Wishlist</a>
        <small>Check the products in your wishlist</small>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="d-flex flex-column ms-2 me-auto">
        <a class="fw-bold" href ="{{route('notifications')}}">Notifications</a>
        <small>Check your notifications</small>
      </div>
    </li>
  </ul>
</div> 
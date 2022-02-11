<div class="w-100 mb-5">
  <h4 class = "mb-4">Administration Panel</h4>
  
  <hr class = "w-100 mb-3">

  <ul class="list-group list-group-numbered">
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="ms-2 me-auto d-flex flex-column">
        <a href = "{{route('profile')}}" class="fw-bold">Profile</a>
        <span>Check or edit your profile</span>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="ms-2 me-auto d-flex flex-column">
        <a href = "{{route('showAllUsers')}}" class="fw-bold">Users Management</a>
        <span>Create, delete or ban/unban users</span>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="ms-2 me-auto d-flex flex-column">
        <a href = "{{route('showAllProducts')}}" class="fw-bold">Products Management</a>
        <span>Add, delete or edit products</span>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="ms-2 me-auto d-flex flex-column">
        <a href = "{{route('showAllOrders')}}" class="fw-bold">Orders Management</a>
        <span>Manage Orders</span>
      </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="ms-2 me-auto d-flex flex-column">
        <a href = "{{route('notifications')}}" class="fw-bold">Notifications</a>
        <span>Check your notifications</span>
      </div>
    </li>
  </ul>
</div>
   


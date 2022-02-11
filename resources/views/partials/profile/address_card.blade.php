<div class="card mb-4">
  <div class="card-body d-flex flex-column">
    <div class="d-flex justify-content-between align-items-center">
      <h6 class="card-title">Address {{$number}}</h6>
      <form class = "m-1" method = "POST" action = {{url('users/addresses/'.$entry->id)}}>
        @csrf
        @method('delete')
  
        <button class="btn" type = "submit">
          <i class="fa fa-times"></i>
        </button>
      </form>
    </div>

    <span>
      {{
        $entry->streetname.' '
        .$entry->streetnumber.' '
        .$entry->aptnumber.' '
        .$entry->floor
      }}
    </span>

    <span>
      {{$entry->zipcode}}
    </span>
  </div>
</div>
<div class="card mb-4">
  <div class="card-body ">
    
    <div class="row">
      <div class="col-md-4">
        <img src={{asset($entry->profilepic)}} alt={{$entry->username}} height = 100>
      </div>
      <div class="col-md-8 d-flex flex-column">
        @if($entry->isadmin)
          <strong class = "text-primary">ADMINISTRATOR</strong>
        @elseif($entry->isbanned)
          <strong class = "text-danger">BANNED</strong>
        @endif
        
        <div>
          <strong>Username: </strong>
          <span>{{$entry->username}}</span>
        </div>
        <div>
          <strong>Email: </strong>
          <span>{{$entry->email}}</span>
        </div>
        
      </div>
    </div>
    <div class="row">
      <div class="col">
        <form class = "m-1" method = "POST" action={{url('admin/users/delete/'.$entry->id)}}>
          @csrf
          @method('delete')
  
          <button class = "btn btn-outline-danger w-100" type = "submit">
            <i class="fa fa-times"></i>
            Delete
          </button>
        </form>
      </div>

      <div class="col">
        <form class = "m-1" method = "POST" action={{url('admin/users/block/'.$entry->id)}}>
          @csrf
          @if(!$entry->isbanned)
          <button class = "btn btn-outline-danger w-100" type = "submit">
            <i class="fa fa-lock"></i>
            Block
          </button>
          @else
          <button class = "btn btn-outline-success w-100" type = "submit">
            <i class="fa fa-unlock"></i>
            Unblock
          </button>
          @endif
        </form> 
      </div>

      <div class="col">
        <form class = "m-1" method = "POST" action={{url('admin/users/edit/'.$entry->id)}}>
          @csrf
  
          <button class = "btn btn-outline-primary w-100" type = "submit">
            <i class="fa fa-pencil-square-o"></i>
            Edit
          </button>
        </form> 
      </div>
    </div>
  </div>
</div>
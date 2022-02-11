<div class="card mb-4">
  <div class="card-body row">
    <div class = "col-10">
     <strong>#{{$i}}</strong> {{$entry->content}}
    </div>
    
    <div class="col-2 justify-content-end">
      @if(!$entry->isread)
        <form method = "POST" action={{url('users/notifications/mark/'.$entry->id)}}>
          @csrf
          @method('delete')
          
          <button class = "btn btn-outline-success" type = "submit">
            <i class="fa fa-check"></i>
            <span>Mark as Read</span>
          </button> 
        </form>
      @endif
    </div>
    
  </div>
</div>
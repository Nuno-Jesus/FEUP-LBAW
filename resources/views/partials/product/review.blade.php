          
<div class="card m4-2 mb-4">
  <div class="card-body">
    @if(!Auth::guest())
      @php $customer = DB::table('customer')->where('id_user', '=', Auth::id())->first();
      @endphp
    @endif
    <div class="d-flex justify-content-between">
      <div>
        @for($i = 0; $i < $entry['content']->rating; $i++)
          <i class="fa fa-star checked text-success"></i>
        @endfor
        
        @for($i = $entry['content']->rating; $i < 5; $i++)
          <i class="fa fa-star-o text-success"></i>
        @endfor
        <span> 
          posted by 
          <strong class = "text-danger">
          @if(!Auth::guest() && !Auth::user()->isadmin)
            @if($customer->id == $entry['content']->id_customer)
              You
            @else
              {{ $entry['user']->username }}
            @endif
          @else
            {{ $entry['user']->username }}
          @endif
          </strong> 
        </span>  
      </div>

      <div class = "d-flex align-items-center">
        @if(!Auth::guest() && !Auth::user()->isadmin)   
          @if($customer->id != $entry['content']->id_customer) 
            <span class = "m-1">Was this review helpful?</span>
            <form class = "m-1" method = "POST" action={{url('reviews/upvote/'.$entry['content']->id)}}>
              @csrf
            
              <button class = "btn btn-outline-success" type = "submit">
                <i class="fa fa-arrow-up"></i>
                {{$entry['content']->yesvotes}}
              </button>
            </form>
            
            <form class = "m-1" method = "POST" action={{url('reviews/downvote/'.$entry['content']->id)}}>
              @csrf
            
              <button class = "btn btn-outline-danger" type = "submit">
                <i class="fa fa-arrow-down"></i>
                {{$entry['content']->novotes}}
              </button>
            </form>
          @endif
        @else
          <span class = "m-1">Was this review helpful?</span>
            <form class = "m-1" method = "POST" action={{url('reviews/upvote/'.$entry['content']->id)}}>
              @csrf
            
              <button class = "btn btn-outline-success" type = "submit">
                <i class="fa fa-arrow-up"></i>
                {{$entry['content']->yesvotes}}
              </button>
            </form>
            
            <form class = "m-1" method = "POST" action={{url('reviews/downvote/'.$entry['content']->id)}}>
              @csrf
            
              <button class = "btn btn-outline-danger" type = "submit">
                <i class="fa fa-arrow-down"></i>
                {{$entry['content']->novotes}}
              </button>
            </form>
        @endif
        
        @if(!Auth::guest() && !Auth::user()->isadmin)    
          @if($customer->id == $entry['content']->id_customer)
            <form class = "m-1" method = "POST" action={{url('reviews/delete/'.$entry['content']->id)}}>
                @csrf
                <button class = "btn btn-outline-danger" type = "submit">
                  <i class="fa fa-times"></i>
                  Delete review
                </button>
            </form>
          @endif
        @endif
        @if(!Auth::guest() && !Auth::user()->isadmin)
          @if($customer->id != $entry['content']->id_customer) 
            <form class = "m-1" method = "POST" action={{url('reviews/report/'.$entry['content']->id)}}>
              @csrf
              <button class= "btn btn-warning" type = "submit">
                <i class="fa fa-flag"></i>
                Report Review 
              </button>
            </form>
          @endif 
        @endif  
      </div>
    </div>
        
    <div class="">
      {{$entry['content']->text}}
    </div>
  
  </div>
</div>

    

    
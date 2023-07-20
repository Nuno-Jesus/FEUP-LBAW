
  <div class="d-flex justify-content-between mb-2">
    <h4>Accounts</h4>

    <form class = "w-50 m-1" method = "POST" action = "{{route('adminSearchUsers')}}">
      @csrf
      
      <input class="form-control me-2" type="search" name = "search" placeholder="Search">
    </form>

    @include('partials.buttons.add_button', 
      ['text' => 'Create User', 'id' => 'createUserButton', 'link' => route('newUser')])
  </div>

  @php($i = 0)
  <div class="row row-columns-3">
    @foreach($entries as $entry)
      <div class="col-lg">
        @include('partials.admin.user_entry', ['entry' => $entry])
      </div>

      @php($i++)
      <!-- Makes columns of 3 -->
      @if($i % 3 == 0)
        </div>
        <div class="row row-columns-3">
      @endif
    @endforeach  

    @for($i = 0; $i < 3 - count($entries) % 3; $i++)
      <div class="col-lg"></div>
    @endfor
  </div>
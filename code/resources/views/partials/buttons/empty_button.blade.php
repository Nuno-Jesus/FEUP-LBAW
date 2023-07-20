<form method = "POST" action={{$link}}>
  @csrf
  @method('delete')
  <button class = "btn btn-danger" id = {{$id}} type = "submit">
    <i class="fa fa-trash" aria-hidden="true"></i>
    {{$text}}
  </button>
</form> 
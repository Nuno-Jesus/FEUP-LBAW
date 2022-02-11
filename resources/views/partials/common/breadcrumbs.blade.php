<nav class = "m-3" aria-label="breadcrumb" id = "breadcrumbs">
  <ol class="breadcrumb">
    @if($current != null || count($breadcrumbs) > 0)
      <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
    @endif
    
    @foreach($breadcrumbs as $path => $name)
      <li class="breadcrumb-item"><a href="{{$path}}">{{$name}}</a></li>
    @endforeach

    @if($current != null)
      <li class="breadcrumb-item" aria-current="page">{{$current}}</li>
    @endif
  </ol>
</nav>
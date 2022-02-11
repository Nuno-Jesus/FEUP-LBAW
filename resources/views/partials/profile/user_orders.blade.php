<div class="d-flex justify-content-between">
  <h4>Your orders</h4>
</div>

@php($i = 0)
<div class="row row-columns-2">
  @foreach($entries as $entry)
    <div class="col-md">
      @include('partials.profile.order_card', ['entry' => $entry])
    </div>

    @php($i++)
    <!-- Makes columns of 2 -->
    @if($i % 2 == 0)
      </div>
      <div class="row row-columns-2">
    @endif
  @endforeach  

  @for($i = 0; $i < 2 - count($entries) % 2; $i++)
    <div class="col-md"></div>
  @endfor
</div>
  

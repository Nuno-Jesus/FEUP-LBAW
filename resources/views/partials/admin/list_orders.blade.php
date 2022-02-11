
  <div class="d-flex justify-content-between">
    <h4>Orders</h4>
  </div>

  @php($i = 0)
  <div class="row row-columns-4">
    @foreach($entries as $entry)
      <div class="col-lg">
        @include('partials.admin.order_entry', ['entry' => $entry])
      </div>

      @php($i++)
      <!-- Makes columns of 4 -->
      @if($i % 4 == 0)
        </div>
        <div class="row row-columns-4">
      @endif
    @endforeach  

    @for($i = 0; $i < 4 - count($entries) % 4; $i++)
      <div class="col-lg"></div>
    @endfor
  </div>

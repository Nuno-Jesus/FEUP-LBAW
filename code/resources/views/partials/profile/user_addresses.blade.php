<div class="d-flex justify-content-between mb-2">
  <h4>Your adresses</h4>
  @include('partials.buttons.add_button', 
      ['text' => 'New Address', 'id' => 'newAddressButton', 'link' => route('newAddress')])
</div>

@php($i = 0)
<div class="row row-columns-3">
  @foreach($entries as $entry)
    <div class="col-md">
      @include('partials.profile.address_card', ['entry' => $entry, 'number' => ($i + 1)])
    </div>

    @php($i++)
    <!-- Makes columns of 3 -->
    @if($i % 3 == 0)
      </div>
      <div class="row row-columns-3">
    @endif
  @endforeach  

  @for($i = 0; $i < 3 - count($entries) % 3; $i++)
    <div class="col-md"></div>
  @endfor
</div>
   

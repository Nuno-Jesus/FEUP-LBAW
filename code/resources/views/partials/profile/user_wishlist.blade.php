<div class="d-flex justify-content-between mb-2">
  <h4>Your wishlist</h4>
  @include('partials.buttons.empty_button', 
    ['text' => 'Empty Wishlist', 'id' => 'emptyWishlistButton', 'link' => route('emptyWishlist')])
</div>

@php($i = 0)
<div class="row row-columns-2">
  @foreach($entries as $entry)
    <div class="col-lg">
      @include('partials.profile.wishlist_card', ['entry' => $entry])
    </div>

    @php($i++)
    <!-- Makes columns of 2 -->
    @if($i % 2 == 0)
      </div>
      <div class="row row-columns-2">
    @endif
  @endforeach  

  @for($i = 0; $i < 2 - count($entries) % 2; $i++)
    <div class="col-lg"></div>
  @endfor
</div>
  

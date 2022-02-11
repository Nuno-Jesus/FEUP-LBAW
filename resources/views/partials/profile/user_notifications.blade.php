<div class="d-flex justify-content-between">
  <h4>Your notifications</h4>
</div>

@php($i = 1)
@foreach($entries as $entry)
  <div class="row">
    @include('partials.profile.notifications_card', ['entry' => $entry, 'i' => $i++])
  </div>
@endforeach  
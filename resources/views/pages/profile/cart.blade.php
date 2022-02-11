@extends('layouts.app')

@section('content')
	<section class = "d-flex flex-column m-5">
		<div class="row mb-2">
			<h4>Your cart</h4>
		</div>

		<section class = "w-100 d-flex justify-content-around">
			<table class = "table w-60">
				<thead>
					<tr class = text-center>
						<th scope = "col">Image</th>
						<th scope = "col">Description</th>
						<th scope = "col">Quantity</th>
						<th scope = "col">Price</th>
					</tr>
				</thead>
				<tbody>
					@foreach($cart as $item)
						@include('partials.cart.entry', ['item' => $item])
					@endforeach
				</tbody>
			</table> 
			
			@include('partials.cart.card')
		</section>
	</section>
@endsection
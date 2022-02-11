@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column">
        <section class = "d-flex flex-column" id = "content">	
            <section class = "w-100 d-flex justify-content-around">
                <form class = "row g-3" id = "payment-form" method="POST" action = {{url('users/cart/checkout')}}>
                @csrf
                    <div class="card-body d-flex justify-content-between">
                        <div class="form-group col-md-4">
                            <label for="payment-type"><span>Payment Type<smal class="required_input">*</small></span></label>
                            <select name= "payment-type" id="payment-type" class="form-control">
                            <option id="payment1" value="Paypal">Paypal</option>
                            <option id="payment2" value="Card">Card</option>
                            </select>
                        </div> 
                        <div class="form-group col-md-4">
                            <label for="addressID"><span>Address<smal class="required_input">*</small></span></label>
                            <select name="addressID" id="addressID" class="form-control">
                            @php($i = 1)
                            @foreach($entries as $entry)
                            <option value={{$entry->id}}> Address {{$i}}</option>
                            @php($i++)
                            @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="card-body d-flex justify-content-between .d-none" id="card-payment-form">
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardName"><span>Card Owner Name<smal class="required_input">*</small></span></label>
                            <input type="text" class="form-control form-control-sm" id="cardName" name="cardName">
                        </div> 
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardNumber"><span>Card Number<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="cardNumber" name="cardNumber" min="0" max="9999999999999999">
                        </div>  
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardExpiration"><span>Expiration Date<smal class="required_input">*</small></span></label>
                            <input type="date" class="form-control form-control-sm" id="cardExpiration" name="cardExpiration" >
                        </div>   
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardCVV"><span>CVV<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="cardCVV" name="cardCVV" min="000" max="999" >
                        </div> 
                    </div>
                    <div class="card-body d-flex justify-content-between" id="paypal-payment-form">
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="paypalEmail"><span>Paypal Email<smal class="required_input">*</small></span></label>
                            <input type="text" class="form-control form-control-sm" id="paypalEmail" pattern = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="paypalEmail">
                        </div> 
                    </div>
                    <input type="submit" class="btn btn-success m-2" value="Buy!">

                    @foreach($errors as $error)
                     <div class="col-12">
                     <div class = "alert alert-danger">
                       <i class="fa fa-times"></i>
                             {{$error}}
                     </div>
                     </div>
                    @endforeach
                </form>
            </section>
            
        </section>
        
    </div>
@endsection
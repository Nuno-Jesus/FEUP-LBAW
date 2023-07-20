@extends('layouts.app')
                    <div class="card-body d-flex justify-content-between">
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardName"><span>Card Owner Name<smal class="required_input">*</small></span></label>
                            <input type="text" class="form-control form-control-sm" id="cardName" required="required">
                        </div> 
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardNumber"><span>Card Number<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="cardNumber" min="0" max="999999999999" required="required">
                        </div>  
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardExpiration"><span>Expiration Date<smal class="required_input">*</small></span></label>
                            <input type="date" class="form-control form-control-sm" id="cardExpiration" required="required">
                        </div>   
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="cardCVV"><span>CVV<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="cardCVV" min="100" max="999" required="required">
                        </div> 
                    </div>
@endsection
@extends('layouts.app')

@section('content')
                    <div class="card-body d-flex justify-content-between">
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="paypalEmail"><span>Paypal Email<smal class="required_input">*</small></span></label>
                            <input type="text" class="form-control form-control-sm" id="paypalEmail" required="required">
                        </div> 
                    </div>
@endsection
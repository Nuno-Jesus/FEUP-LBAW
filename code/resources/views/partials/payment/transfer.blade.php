@extends('layouts.app')

@section('content')
                    <div class="card-body d-flex justify-content-between">
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="transferEntity"><span>Transfer Entity<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="transferEntity" required="required">
                        </div> 
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="transferReference"><span>Transfer Reference<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="transferReference" required="required">
                        </div>  
                        <div class="form-group col-sm-3 col-form-label">
                            <label for="transferValidFor"><span>Valid For<smal class="required_input">*</small></span></label>
                            <input type="number" class="form-control form-control-sm" id="transferValidFor" required="required">
                        </div>   
                    </div>
@endsection
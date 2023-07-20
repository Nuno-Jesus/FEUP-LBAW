<div class="col w-60 d-flex flex-column">
  <div class="d-flex justify-content-between mb-2">
    <h4>New address</h4>
  </div>

  <form class = "row g-3" method = "POST" action= {{url('users/addresses/new')}}>
    @csrf

    <div class="col-12">
      <label for="streetName" class="form-label">
        <span>Street Name</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="streetName" id="streetName" placeholder="Saint Louis St." required>
    </div>

    <div class="col-md-4">
      <label for="streetNumber" class="form-label">
        <span>Street Number</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="streetNumber" id="streetNumber" placeholder="123" required>
    </div>

    <div class="col-md-4">
      <label for="aptNumber" class="form-label">
        <span>Apartement Number</span> 
      </label>
      <input type="text" class="form-control" name="aptNumber" id="aptNumber" placeholder = "10">
    </div>

    <div class="col-md-4">
      <label for="floor" class="form-label">
        <span>Floor</span>
      </label>
      <input type="text" class="form-control" name="floor" id="floor" placeholder = "1">
    </div>

    <div class="col-md-4">
      <label for="zipcodeNumber" class="form-label">
        <span>Zip Code</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="zipcodeNumber" id="zipcodeNumber" placeholder = "4321-567" required>
    </div>

    <div class="col-md-8">
      <label for="zipcodeLocation" class="form-label">
        <span>Zip Code Location</span>
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="zipcodeLocation" id="zipcodeLocation" placeholder = "Bullevard" required>
    </div>

    <div class="col-12">
      <button class = "btn btn-outline-success" type = "submit">
        Submit
      </button>
    </div>
    
    @foreach($errors as $error)
      <div class="col-12">
        <div class = "alert alert-danger">
          <i class="fa fa-times"></i>
          {{$error}}
        </div>
      </div>
    @endforeach
  </form>
</div>
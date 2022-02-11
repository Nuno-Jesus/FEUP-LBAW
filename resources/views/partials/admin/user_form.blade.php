<div class="mb-5">
  <div class="d-flex justify-content-between mb-2">
    <h4>New account</h4>
  </div>

  <form class = "row g-3" method = "POST" action= {{route('createUser')}}>
    @csrf

    <div class="col-md-6">
      <label for="username" class="form-label">
        <span>Username</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="username" id="username" placeholder="(8 characters min)" required>
    </div>

    <div class="col-md-6">
      <label for="email" class="form-label">
        <span>Email</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="email" id="email" placeholder="myemail@example.com" required>
    </div>

    <div class="col-md-6">
      <label for="password" class="form-label">
        <span>Password</span> 
        <small class = "required-input">*</small>
      </label>
      <input type="text" class="form-control" name="password" id="password" placeholder = "(8 characters min)" required>
    </div>
    
    <div class="col-md-6">
      <label for="phone" class="form-label">
        <span>Phone</span> 
      </label>
      <input type="text" class="form-control" name="phone" id="phone" placeholder = "912345678">
    </div>

    <div class="col-md-2">
      <label for="admin" class="form-label">
        <span>Administrator Privileges</span>
        <small class = "required-input">*</small>
      </label>
      <select name="admin" id="admin" required>
        <option value="false">No</option>
        <option value="true">Yes</option>
      </select>
    </div>

    <div class="col-12">
      <button class = "btn btn-outline-success" type = "submit">
        Submit
      </button>
    </div>
    
    @foreach($errors as $error)
      @include('partials.forms.error', ['error' => $error])
    @endforeach
  </form>
</div>
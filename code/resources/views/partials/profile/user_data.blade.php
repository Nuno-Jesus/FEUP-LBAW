<div class="w-100 mt-3 mb-5">
  <h4>Your account data</h4>
  <div class="row">
    <div class="col-lg-4">
      <div class="d-flex flex-column align-items-center">
        <img class = "rounded-circle" src = "{{asset($user->profilepic)}}" alt = "Your profile picture">
        <div class="d-flex align-items-center">
          <form class="m-1" method = "POST" action = "{{route('upload')}}" >
            @csrf
            
            <label class = "btn btn-outline-primary" for="photo" id = "uploadPhotoInput">
              <i class="fa fa-file-image-o"></i>
              Upload Picture
            </label>
            <input class="form-control-file m-1" enctype="multipart/form-data" type="file" name = "photo" id="photo" hidden>
            <button class = "btn btn-outline-success m-1" type = "submit">
              <i class="fa fa-check"></i>
              Submit
            </button>
          </form>
                
          <form class="m-1" method = "POST" action = {{route('deleteProfile')}}>
            @csrf
            @method('delete')
            <button class = "btn btn-outline-danger" type = "submit">
              <i class="fa fa-trash"></i>
              Delete Account
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <div class="col-lg-8">
      <form class = "d-flex mt-3 w-100" id = "profile-form" method = "POST" action = "{{url('/users/edit/'.$user->id)}}">
        @csrf
    
        <div class="container-fluid">
          <div class="form-group">
            <label for="username"><h6>Username</h6></label>
            <input type="text" class="form-control" id="username" name="username" value="{{$user->username}}" disabled>
          </div>
          
          <div class="form-group mt-3">
            <label for="password"><h6>Password</h6></label>
            <input type="password" class="form-control" id="password" name="password" placeholder = "*********" disabled>
          </div>
      
          <div class="form-group mt-3">
            <label for="email"><h6>Email</h6></label>
            <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" disabled>
          </div>
      
          <div class="form-group mt-3">
            <label for="phone"><h6>Phone</h6></label>
            <input type="phone" class="form-control" id="phone" name="phone" value="{{$user->phone}}" disabled>
          </div>
    
          <div class="d-flex justify-content-between">
            <span class = "btn btn-primary mt-2" id = "editProfileButton">Edit</span>
            <button class="btn btn-outline-success m-1" type="submit"  id = "profileSubmitButton" hidden>Submit</button>
          </div> 
        </div>
      </form>
    </div>
  </div>

</div>    
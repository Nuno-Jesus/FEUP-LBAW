<div class="user-photo d-flex flex-column">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4>{{$user->username}}</h4>
      <span id = "userId" hidden>{{$user->id}}</span>
    </div>

    <label class = "btn btn-primary" for="profilePic">Upload a picture</label>
    <input type="file" class="form-control-file" id="profilePic" hidden>
  </div>
  
  <img src = "{{asset($user->profilepic)}}" alt = "Your profile picture">
</div>
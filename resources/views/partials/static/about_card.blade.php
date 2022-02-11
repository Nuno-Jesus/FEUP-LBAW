<div class="aboutus-column">
  <div class="aboutus-card">
      <img class = "m-2" id= "DeveloperPhoto" src="{{$image}}" alt="{{$name}}">
      <div class="aboutus-container">
          <h2>{{$name}}</h2>
          <p class="aboutus-title">CEO and Developer</p>
          <p>{{$text}}</p>
          <p>{{$email}}</p>
          <button class = "btn btn-outline-primary w-50 m-2">
              <a class="w-50 m-2" href={{"mailto:".$email."?subject=Question about your website"}}> Contact me! </a>
          </button>
      </div>
  </div>
</div>
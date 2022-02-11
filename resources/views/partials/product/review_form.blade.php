<form class = "card border-primary mt-4 mb-5" method = "POST" action = {{url('reviews/submit/'.$user->id.'/'.$product->id)}}>
  @csrf
  
  <h4 class="card-title  m-2">
    Write your review!
  </h4>
  <div class="form-group m-2">
    <label for="rating"><strong>Rating</strong></label>
    <select class="form-control" name = "rating" id="rating">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>

  <div class="form-group m-2">
    <label class = "" for="comment"><strong>Your review</strong></label>
    <textarea class="form-control" name = "comment" id="comment" rows="3"></textarea>
  </div>

  <div class="form-group m-2">
    <button class="btn btn-outline-success" type = "submit">Submit</button>
  </div>
</form>
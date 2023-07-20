<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistPolicy
{
  use HandlesAuthorization;
  
  public function show(User $user)
  {
    return Auth::id() == $user->id; 
  }

  public function add(User $user)
  {
    return Auth::id() == $user->id; 
  }

  public function delete(User $user)
  {
    return Auth::id() == $user->id; 
  }

  public function empty(User $user)
  {
    return Auth::id() == $user->id; 
  }
}

?>
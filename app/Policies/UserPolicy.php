<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
  use HandlesAuthorization;
  
  public function show(User $user)
  {
    return Auth::id() == $user->id || Auth::user()->isadmin; 
  }
  
  public function update(User $user)
  {
    return Auth::id() == $user->id || Auth::user()->isadmin;
  }
  
  public function delete(User $user)
  {
    return Auth::id() == $user->id || Auth::user()->isadmin;
  }
}

?>
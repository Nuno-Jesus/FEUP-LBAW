<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
  use HandlesAuthorization;
  
  public function show(User $user)
  {
    return Auth::id() == $user->id || Auth::user()->isadmin; 
  }
}

?>
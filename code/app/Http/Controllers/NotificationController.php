<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{ 
  public function show(){
    $user = User::find(Auth::id());
    $notifications = Notification::
      where('id_user', '=', $user->id)
    ->get();

    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.profile.user_notifications',
      'entries' => $notifications,
      'breadcrumbs' => [route('profile') => Auth::user()->username],
      'current' => 'Notifications'
    ]);
  }

  public function mark($id){
    $notification = Notification::find($id);
    
    $notification->delete();

    return redirect()->back();
  }
}

?>

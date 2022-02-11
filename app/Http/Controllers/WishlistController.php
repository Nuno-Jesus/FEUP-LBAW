<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{ 
  private function getCustomer(){
    return Customer::where('id_user', '=', Auth::id())->first();
  }

  private function getWishlistEntry($id){
    $customer = $this->getCustomer();
    
    return Wishlist::where('id_customer', '=', $customer->id)
      ->where('id_product','=', $id);
  }

  private function getFullWishlist(){
    $customer = $this->getCustomer();
    return Wishlist::where('id_customer', '=', $customer->id)->get();
  }

  public function show(){
    //$this->authorize('show', Auth::user());
    $wishlist = $this->getFullWishlist();
    $entries = array();

    foreach($wishlist as $product){
      array_push($entries, Product::find($product->id_product));
    }

    return view('pages.profile.user_profile', [
      'user' => User::find(Auth::id()),
      'entries' => $entries,
      'content' => 'partials.profile.user_wishlist',
      'breadcrumbs' => [route('profile') => User::find(Auth::id())->username],
      'current' => 'Wishlist'
    ]);
  }
  
  public function add($product_id){
    //$this->authorize('add', Auth::user());
    if(!Auth::check())
      return redirect(route('login'));

    $customer = $this->getCustomer();

    $entry = new Wishlist;

    $entry->id_customer = $customer->id;
    $entry->id_product = $product_id;

    $entry->save();

    return redirect()->back();
  }

  public function delete($product_id){
    //$this->authorize('delete', Auth::user());
    $entry = $this->getWishlistEntry($product_id);

    $entry->delete();

    return redirect()->back();
  }

  public function empty(){
    //$this->authorize('empty', Auth::user());
    $entries = $this->getFullWishlist();

    foreach($entries as $entry){
      $entry->delete();
    }

    return redirect()->back();
  }
}
?>
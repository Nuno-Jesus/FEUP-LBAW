<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{ 
  private function getCart(){
    $entry = Customer::select('id')
    ->where('id_user', '=', Auth::id())
    ->first();


    return Cart::where('id_customer', '=', $entry->id)
    ->where('isactive', '=', true)
    ->first();
  }

  private function getCartEntry($id){
    $cart = $this->getCart();

    return CartProduct::where('id_cart', '=', $cart->id)
    ->where('id_product', '=', $id);
  }

  private function getAllCartEntries(){
    $cart = $this->getCart(); 
    return CartProduct::where('id_cart', '=', $cart->id)->get();
  }

  public function show(){
    $user = User::find(Auth::id());
    $cartProducts = $this->getAllCartEntries();
    $cart = array();

    $total = 0;
    foreach($cartProducts as $entry){
      $product = Product::find($entry->id_product);
      array_push(
        $cart,
        [
          'product' => $product,
          'quantity' => $entry->quantity
        ]
      );

      $total += $product->price * $entry->quantity;
    }

    return view('pages.profile.cart', [
      'user' => $user,
      'cart' => $cart,
      'total' => $total,
      'breadcrumbs' => [route('profile') => $user->username],
      'current' => 'Cart'
    ]);
  }

  public function add($product_id)
  { 
    //! If the user is not authenticated, he is redirected to the login page
    if(!Auth::check())
      return redirect(route('login'));

    $entry = new CartProduct();

    $entry->id_cart = $this->getCart()->id;
    
    $entry->id_product = (int)$product_id;
    $entry->quantity = 0;
    
    $entry->quantity++;
    $entry->save();

    return redirect()->back();
  }

  public function delete($product_id)
  {
    $entry = $this->getCartEntry($product_id);
    //dd($entry);
    $entry->delete();
    return redirect()->back();
  }

  public function incrementQuantity($product_id)
  {
    $entry = $this->getCartEntry($product_id);

    $entry->quantity++;
    $entry->save();
    
    return redirect()->back();
  }

  public function decrementQuantity($product_id)
  {
    $entry = $this->getCartEntry($product_id);

    $entry->quantity--;
    $entry->save();

    return redirect()->back();
  }

  public function empty()
  {
    $cart = $this->getAllCartEntries();
    
    foreach($cart as $entry){
      $entry->delete();
    }

    return redirect()->back();
  }
}
?>

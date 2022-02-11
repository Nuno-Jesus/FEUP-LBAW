<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Purchase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{ 
  private function getCustomer(){
    return Customer::where('id_user', '=', Auth::id())->first();
  }

  public function show(){
    //$this->authorize('show', Auth::user());
    $user = $this->getCustomer();
    $orders = $user->Purchases;
    $entries = array();
    $products = array();
    
    //Gets every order of the user
    foreach($orders as $order){
      //Finds all the entries of the cart of the current order
      $cartEntries = CartProduct::where('id_cart', '=', $order->id_cart)->get();
      
      $total = 0;
      $products = array();

      //Finds the prices and quantities of all products in the cart
      foreach($cartEntries as $entry){
        $product = Product::find($entry->id_product);
        $total += $entry->quantity * $product->price;
        array_push($products, $product);
      }

      array_push($entries,
      [
        'order' => $order,
        'products' => $products,
        'address' => Address::find($order->id_address),
        'total' => $total
      ]);
    }

    return view('pages.profile.user_profile', [
      'user' => $user,
      'content' => 'partials.profile.user_orders',
      'entries' => $entries,
      'breadcrumbs' => [route('profile') => Auth::user()->username],
      'current' => 'Orders'
    ]);
  }

  public function cancel($id){
    $purchase = Purchase::find($id);

    if ($purchase->orderstatus == 'Processing')
      $purchase->orderstatus = 'Cancelled by Customer';

    $purchase->save();

    return redirect()->back();
  }
}
?>

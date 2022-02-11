<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Card;
use App\Models\Paypal;
use App\Models\Transfer;
use App\Models\PaymentMethod;
use App\Models\Purchase;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{ 
  public function getCustomer(){
    return Customer::where('id_user', '=', Auth::id())->first();
  }

  public function getCart(){
    $entry = Customer::select('id')
    ->where('id_user', '=', Auth::id())
    ->first();

    return Cart::where('id_customer', '=', $entry->id)
    ->where('isactive', '=', true)
    ->first();
  }

  public function getAllCustomerAddresses(){
    $customer = $this->getCustomer();
    return CustomerAddress::where('id_customer', '=', $customer->id)->get();
  }

  public function checkout(){
    $customerAddresses = $this->getAllCustomerAddresses();
    $entries = array();

    foreach($customerAddresses as $customerAddress){
      array_push(
        $entries,
        Address::find($customerAddress->id_address)
      );
    }

    return view('partials.cart.checkout', 
    [
      'user' => User::find(Auth::id()),
      //'content' => 'partials.profile.user_addresses',
      'entries' => $entries,
      'breadcrumbs' => [route('checkout') => Auth::user()->username],
      'current' => 'Checkout'
    ]);
  }

  public function add(Request $request){
    $errors = array();

    $ptype = $request->input('payment-type');
    $payment = new PaymentMethod;
    $payment->type = $ptype;
    $payment->save();
    
    switch ($ptype) {
      case "Paypal":
        $ppEmail = $request->input('paypalEmail');
        $paypal = new Paypal;
        $paypal->email = $ppEmail;
        $paypal->id_paymentmethod = $payment->id;
        $paypal->save();
        break;
      case "Card":
        $cname = $request->input('cardName');
        $cnumber = $request->input('cardNumber');
        $cexpdate = $request->input('cardExpiration');
        $ccvv = $request->input('cardCVV');

        $card = new Card;
        $card->name = $cname;
        $card->number = $cnumber;
        $card->expdate = $cexpdate;
        $card->cvv = $ccvv;
        $card->id_paymentmethod = $payment->id;
        $card->save();
        break;
    }


    
    $purchase = new Purchase;
    $address = $request->input('addressID');
    $cart = $this->getCart();
    $user = $this->getCustomer();
    $orderdate = '2022-01-20';
    $deliverydate = '2022-01-31';

    $purchase->orderdate = $orderdate;
    $purchase->deliverydate = $deliverydate;
    $purchase->id_customer = $user->id;
    $purchase->id_address = $address;
    $purchase->id_paymentmethod = $payment->id;
    $purchase->id_cart = $cart->id;
    $purchase->save();

    return redirect('/');
  }

}
?>

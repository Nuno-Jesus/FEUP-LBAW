<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AddressController extends Controller
{ 
  private function getCustomer(){
    return Customer::where('id_user', '=', Auth::id())->first();
  }

  private function getCustomerAddressEntry($id){
    $customer = $this->getCustomer();
    
    return CustomerAddress::where('id_customer', '=', $customer->id)
      ->where('id_address','=', $id);
  }

  private function getAllCustomerAddress(){
    $customer = $this->getCustomer();
    return CustomerAddress::where('id_customer', '=', $customer->id)->get();
  }

  public function showAddressForm(){
    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.profile.address_form',
      'entries' => [],
      'breadcrumbs' => [
        route('profile') => Auth::user()->username,
        route('addresses') => 'Addresses'
      ],
      'current' => 'New'
    ]);
  }

  public function show(){
    //$this->authorize('show', Auth::user());
    $customerAddresses = $this->getAllCustomerAddress();
    $entries = array();

    foreach($customerAddresses as $customerAddress){
      array_push(
        $entries,
        Address::find($customerAddress->id_address)
      );
    }

    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.profile.user_addresses',
      'entries' => $entries,
      'breadcrumbs' => [route('profile') => Auth::user()->username],
      'current' => 'Addresses'
    ]);
  }

  public function add(Request $request){
    //$this->authorize('add', Auth::user());
    $errors = array();

    $streetName = $request->input('streetName');
    $streetNumber = $request->input('streetNumber');
    $aptNumber = $request->input('aptNumber');
    $floor = $request->input('floor');
    $zipcodeNumber = $request->input('zipcodeNumber');
    $zipcodeLocation = $request->input('zipcodeLocation');

    /********************* INPUT VALIDATION ********************/

    if(!is_numeric($streetNumber)){
      array_push($errors, 'Street Number does not match the specified format.');
    }

    if(!is_numeric($floor) && !is_null($floor)){
      array_push($errors, 'Floor does not match the specified format.');
    }

    $regex = '/^[0-9]{4}-[0-9]{3}$/';
    if(!preg_match($regex, $zipcodeNumber)){
      array_push($errors, 'Zipcode does not match the specified format.');
    }

    if(count($errors) != 0){
      return view('pages.profile.user_profile', 
      [
        'user' => User::find(Auth::id()),
        'content' => 'partials.profile.address_form',
        'entries' => [],
        'breadcrumbs' => [
          route('profile') => Auth::user()->username,
          route('addresses') => 'Addresses'
        ],
        'current' => 'New',
        'errors' => $errors
      ]);
    }

    /***********************************************************/
    
    //Insert on the address table first and then...
    $address = new Address;

    $address->streetname = $streetName;
    $address->streetnumber = $streetNumber;
    $address->aptnumber = $aptNumber;
    $address->floor = $floor;
    $address->zipcode = $zipcodeNumber.' '.$zipcodeLocation;

    $address->save();

    //... insert on intermediate table
    $customer = $this->getCustomer(); 
    $customerAddress = new CustomerAddress;

    $customerAddress->id_customer = $customer->id;
    $customerAddress->id_address = $address->id;

    $customerAddress->save();

    return redirect(route('addresses'));
  }

  public function delete($address_id){
    //$this->authorize('delete', Auth::user());
    
    $entry = $this->getCustomerAddressEntry($address_id);
    $entry->delete();
    
    return redirect()->back();
  }
}

?>
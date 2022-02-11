<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CPU;
use App\Models\Cooler;
use App\Models\Product;
use App\Models\GPU;
use App\Models\Motherboard;
use App\Models\PowerSupply;
use App\Models\Storage;
use App\Models\PcCase; 
use App\Models\Other;
use App\Models\Purchase;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
  private function getCart($id){
    return Cart::find($id);
  }

  private function getCartEntries($id){
    return CartProduct::where('id_cart', '=', $id)->get();
  }

  public function showAllUsers(){
    $admin = User::find(Auth::id());
    $users = User::all();   

    return view('pages.profile.user_profile', [
      'user' => $admin,
      'entries' => $users,
      'content' => 'partials.admin.list_users',
      'breadcrumbs' => [route('profile') => $admin->username],
      'current' => 'Users'
    ]);
  }

  public function showAllOrders(){
    $admin = User::find(Auth::id());
    $orders = Purchase::all();

    $entries = array();

    //For each order in the database
    foreach($orders as $order){
      //Gets all the products in the cart associated to the order
      $cartEntries = $this->getCartEntries($order->id_cart);
      
      $total = 0;
      $products = array();

      //Finds all the entries of the cart of the current order
      foreach($cartEntries as $entry){
        $product = Product::find($entry->id_product);
        $total += $entry->quantity * $product->price;
        array_push($products, $product);
      }
      //For each order pushes the products within, the linked address and the total of the order
      array_push($entries,
      [
        'order' => $order,
        'products' => $products,
        'address' => Address::find($order->id_address),
        'total' => $total
      ]);
    }

    //dd($entries);
    //$this->authorize('show', Auth::user());

    return view('pages.profile.user_profile', [
      'user' => $admin,
      'content' => 'partials.admin.list_orders',
      'entries' => $entries,
      'breadcrumbs' => [route('profile') => $admin->username],
      'current' => 'Orders'
    ]);
  }
  
  public function showAllProducts(){
    $admin = User::find(Auth::id());
    $products = Product::all();   

    return view('pages.profile.user_profile', [
      'user' => $admin,
      'entries' => $products,
      'content' => 'partials.admin.list_products',
      'breadcrumbs' => [route('profile') => $admin->username],
      'current' => 'Products'
    ]);
  }

  public function getCreateUserForm(){
    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.admin.user_form',
      'breadcrumbs' => [
        route('profile') => Auth::user()->username,
        route('showAllUsers') => 'Users',
      ],
      'entries' => [],
      'current' => 'Create User'
    ]);
  } 
  
  public function createUser(Request $request){
    $errors = array();
    
    $username = $request->input('username');
    $email = $request->input('email');
    $password = $request->input('password');
    $phone = $request->input('phone');
    $isadmin = $request->input('admin');

    if(strlen($username) < 8)
      array_push($errors, "The username is too small (must be at least 8 characters long).");

    $regex = '/[a-zA-Z0-9]+@[a-zA-Z]+(.[a-zA-Z]+)+/';
    if(!preg_match($regex, $email))
      array_push($errors, "The email doesn't fit the required format.");

    if(strlen($password) < 8)
      array_push($errors, "The password is too small (must be at least 8 characters long).");

    if(count($errors) != 0){
      return view('pages.profile.user_profile', 
      [
        'user' => User::find(Auth::id()),
        'content' => 'partials.admin.user_form',
        'breadcrumbs' => [route('profile') => Auth::user()->username],
        'entries' => [],
        'current' => 'Create User',
        'errors' => $errors
      ]);
    }     

    $user = new User;

    $user->username = $username;
    $user->email = $email;
    $user->password = bcrypt($password);
    $user->phone = $phone;
    $isadmin == "false" ? $user->isadmin = false : $user->isadmin = true;
    $user->save();
    

    if(!($user->isadmin)){
      $customer = new Customer;
      $customer->id_user = $user->id;
      $customer->save();
    }

    return redirect(route('showAllUsers'));
  }

  public function deleteUser($user_id){
    $user = User::find($user_id);
    $user->delete();
    
    return redirect(route('showAllUsers'));
  }
  
  public function editUser(Request $request, $user_id){
  }

  public function editOrder(Request $request, $order_id){
    $order = Purchase::find($order_id);
    //dd($request->input('orderStatus'));

    $order->orderstatus = $request->input('orderStatus');
    $order->save();

    return redirect()->back();
  }

  public function getEditProductForm($product_id){
    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.admin.product_edit_form',
      'breadcrumbs' => [
        route('profile') => Auth::user()->username,
        route('showAllProducts') => 'Products',
      ],
      'entries' => [Product::find($product_id)],
      'current' => 'Edit Product'
    ]);
  }  

  public function editProduct(Request $request, $product_id){
      $product = Product::find($product_id);

      $errors = array();
      $name = $request->input('name');
      $price = $request->input('price');
      $size = $request->input('size');
      $stock = $request->input('stock');
      $brand = $request->input('brand');
      $description = $request->input('description');

      /********************* INPUT VALIDATION ********************/

      $regex = '/[0-9]+.?[0-9]+/';
      if(!preg_match($regex, $price)){
        array_push($errors, 'Price does not match the specified format.');
      }

      $regex = '/[0-9]+x[0-9]+x[0-9]/';
      if(!preg_match($regex, $size) && !is_null($size)){
        array_push($errors, 'Size does not match the specified format.');
      }

      if (!is_numeric($stock) && $stock < 0){
        array_push($errors, 'Stock does not match the specified format or is negative.');
      }

      if(count($errors) != 0){
        return view('pages.profile.user_profile', 
        [
          'user' => User::find(Auth::id()),
          'content' => 'partials.admin.product_edit_form',
          'breadcrumbs' => [route('profile') => Auth::user()->username],
          'entries' => [Product::find($product_id)],
          'current' => 'Create User',
          'errors' => $errors
        ]);
      }


      $product->name = $name;
      //$product->category = $category;
      $product->price = floatval($price);
      $product->size = $size;
      $product->stock = $stock;
      $product->brand = $brand;
      $product->description = $description;
      $product->save();

      

      $field1 = $request->input('field1');
      $field2 = $request->input('field2');
      $field3 = $request->input('field3');
      $field4 = $request->input('field4');
      $field5 = $request->input('field5');
      
      //... insert on specialized table
      switch ($product->category) {
        case "CPU":
          $newErrors = $this->editCpu($field1, $field2, $field3, $field4, $field5, $product->id);
          break;
        case "GPU":
          $newErrors = $this->editGpu($field1, $field2, $field3, $field4, $field5, $product->id);
          break;
        case "Motherboard":
          $newErrors = $this->editMotherboard($field1, $field2, $product->id);
          break;
        case "PcCase":
          $newErrors = $this->editPcCase($field1, $field2, $field3, $product->id);
          break;
        case "PowerSupply":
          $newErrors = $this->editPowerSupply($field1, $field2, $field3, $product->id);
          break;
        case "Cooler":
          $newErrors = $this->editCooler($field1, $product->id);
          break;
        case "Storage":
          $newErrors = $this->editStorage($field1, $field2, $product->id);
          break;
        case "Other":
          $newErrors = $this->editOther($product->id);
          break;
      }

      $errors = array_merge($errors, $newErrors);
      if(count($errors) != 0){
        return view('pages.profile.user_profile', 
        [
          'user' => User::find(Auth::id()),
          'content' => 'partials.admin.product_edit_form',
          'breadcrumbs' => [route('profile') => Auth::user()->username],
          'entries' => [Product::find($product_id)],
          'current' => 'Create User',
          'errors' => $errors
        ]);
      }
      

      return redirect(route('showAllProducts'));

      /***********************************************************/
  }
  
  public function getCreateProductForm(){
    return view('pages.profile.user_profile', 
    [
      'user' => User::find(Auth::id()),
      'content' => 'partials.admin.product_create_form',
      'breadcrumbs' => [
        route('profile') => Auth::user()->username,
        route('showAllProducts') => 'Products',
      ],
      'entries' => [],
      'current' => 'Create Product'
    ]);
  }  

  public function createProduct(Request $request){
     //$this->authorize('add', Auth::user());
    $errors = array();
    $name = $request->input('name');
    $price = $request->input('price');
    $size = $request->input('size');
    $stock = $request->input('stock');
    $category = $request->input('category');
    $brand = $request->input('brand');
    //$image =
    $description = $request->input('description');


    /********************* INPUT VALIDATION ********************/

    $regex = '/[0-9]+.?[0-9]+/';
    if(!preg_match($regex, $price)){
      array_push($errors, 'Price does not match the specified format.');
    }

    $regex = '/[0-9]+x[0-9]+x[0-9]/';
    if(!preg_match($regex, $size) && !is_null($size)){
      array_push($errors, 'Size does not match the specified format.');
    }

    if (!is_numeric($stock) && $stock < 0){
      array_push($errors, 'Stock does not match the specified format or is negative.');
    }

    /***********************************************************/
    
    if(count($errors) != 0){
      return view('pages.profile.user_profile', 
      [
        'user' => User::find(Auth::id()),
        'content' => 'partials.admin.product_create_form',
        'breadcrumbs' => [route('profile') => Auth::user()->username],
        'entries' => [],
        'current' => 'Create User',
        'errors' => $errors
      ]);
    }
    
    //Insert on the product table first and then...
    $product = new Product;

    $product->name = $name;
    $product->category = $category;
    $product->price = floatval($price);
    $product->size = $size;
    $product->stock = $stock;
    $product->brand = $brand;
    $product->description = $description;
    $product->image = 'images/default.jpg';

    $product->save();
    
    $field1 = $request->input('field1');
    $field2 = $request->input('field2');
    $field3 = $request->input('field3');
    $field4 = $request->input('field4');
    $field5 = $request->input('field5');
    
    //... insert on specialized table
    switch ($category) {
      case "CPU":
        $newErrors = $this->addCpu($field1, $field2, $field3, $field4, $field5, $product->id);
        break;
      case "GPU":
        $newErrors = $this->addGpu($field1, $field2, $field3, $field4, $field5, $product->id);
        break;
      case "Motherboard":
        $newErrors = $this->addMotherboard($field1, $field2, $product->id);
        break;
      case "PcCase":
        $newErrors = $this->addPcCase($field1, $field2, $field3, $product->id);
        break;
      case "PowerSupply":
        $newErrors = $this->addPowerSupply($field1, $field2, $field3, $product->id);
        break;
      case "Cooler":
        $newErrors = $this->addCooler($field1, $product->id);
        break;
      case "Storage":
        $newErrors = $this->addStorage($field1, $field2, $product->id);
        break;
      case "Other":
        $newErrors = $this->addOther($product->id);
        break;
    }

    $errors = array_merge($errors, $newErrors);
    if(count($errors) != 0){
      return view('pages.profile.user_profile', 
      [
        'user' => User::find(Auth::id()),
        'content' => 'partials.admin.product_create_form',
        'breadcrumbs' => [route('profile') => Auth::user()->username],
        'entries' => [],
        'current' => 'Create User',
        'errors' => $errors
      ]);
    }
    
    return redirect()->back();
  }

  public function deleteProduct($product_id){
    $product = Product::find($product_id);
    $product->delete();

    return redirect()->back();
  }


  // Auxiliary functions
  public function addCpu($baseFreq, $turboFreq, $socket, $threads, $cores, $id){
    $errors = array();
    $cpu = new CPU;
    
    $regex = '/[0-9]+.?[0-9]+/';
    if(!preg_match($regex, $baseFreq) || is_null($baseFreq))
      array_push($errors, 'CPU: Field 1 (Base Frequency) must be a float.');

    if(!preg_match($regex, $turboFreq) || is_null($turboFreq))
      array_push($errors, 'CPU: Field 2 (Turbo Frequency) must be a float.');
    
    if(!is_numeric($threads) || is_null($threads))
      array_push($errors, "CPU: Field 4 (Threads) must be an integer.");

    if(!is_numeric($cores) || is_null($cores))
      array_push($errors, "CPU: Field 5 (Cores) must be an integer.");

    if(count($errors) != 0)
      return $errors;

    $cpu->basefreq = $baseFreq;
    $cpu->turbofreq = $turboFreq;
    $cpu->socket = $socket;
    $cpu->threads = $threads;
    $cpu->cores = $cores;
    $cpu->id_product = $id;
    
    $cpu->save();
    return $errors;
  }
  
  public function addGpu($memory, $coreClock, $boostClock, $hdmiPorts, $displayPorts, $id){
    $errors = array();
    $gpu = new GPU;

    if(!is_numeric($memory) || is_null($memory))
      array_push($errors, "GPU: Field 1 (Memory) must be an integer.");

    if(!is_numeric($coreClock) || is_null($coreClock))
      array_push($errors, "GPU: Field 2 (Core Clock) must be an integer.");

    if(!is_numeric($boostClock) || is_null($boostClock))
      array_push($errors, "GPU: Field 3 (Boost Clock) must be an integer.");

    if(!is_numeric($hdmiPorts) || is_null($hdmiPorts))
      array_push($errors, "GPU: Field 4 (HDMI Ports) must be an integer.");

    if(!is_numeric($displayPorts) || is_null($displayPorts))
      array_push($errors, "GPU: Field 5 (Display Ports) must be an integer.");

    if(count($errors) != 0)
      return $errors;
    
    $gpu->memory = $memory;
    $gpu->coreclock = $coreClock;
    $gpu->boostclock = $boostClock;
    $gpu->hdmiports = $hdmiPorts;
    $gpu->displayports = $displayPorts;
    $gpu->id_product = $id;

    $gpu->save();
    return $errors;
  }
  
  public function addMotherboard($chipset, $type, $id){
    $errors = array();
    $motherboard = new Motherboard;

    if(is_null($chipset))
      array_push($errors, "Motherboard: Field 1 (Chipset) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Motherboard: Field 2 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    $motherboard->chipset = $chipset;
    $motherboard->type = $type;
    $motherboard->id_product = $id;

    $motherboard->save();
    return $errors;
  }

  public function addPcCase($color, $weight, $type, $id){
    $errors = array();
    $pcCase = new PcCase;

    if(is_null($color))
      array_push($errors, "Desktop Case: Field 1 (Color) can't be empty.");
    
    if(is_null($weight))
      array_push($errors, "Desktop Case: Field 2 (Weight) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Desktop Case: Field 3 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    $pcCase->color = $color;
    $pcCase->weight = $weight;
    $pcCase->type = $type;
    $pcCase->id_product = $id;

    $pcCase->save();
    return $errors;
  }
    
  public function addPowerSupply($wattage, $certification, $type, $id){
    $errors = array();
    $powerSupply = new PowerSupply;
    
    if(is_null($wattage))
      array_push($errors, "Power Supply: Field 1 (Color) can't be empty.");
    
    if(is_null($certification))
      array_push($errors, "Power Supply: Field 2 (Certification) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Power Supply: Field 3 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;
    
    $powerSupply->wattage = $wattage;
    $powerSupply->certification = $certification;
    $powerSupply->type = $type;
    $powerSupply->id_product = $id;

    $powerSupply->save();
    return $errors;
  }

  public function addCooler($type, $id){
    $errors = array();
    $cooler = new Cooler;
    
    if(is_null($type))
      array_push($errors, "Cooler: Field 1 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    $cooler->type = $type;
    $cooler->id_product = $id;

    $cooler->save();
    return $errors;
  }

  public function addStorage($capacity, $type, $id){
    $errors = array();
    $storage = new Storage;

    if(!is_numeric($capacity))
      array_push($errors, "Storage: Field 1 (Capacity) must be an integer.");
    
    if(is_null($type))
      array_push($errors, "Storage: Field 2 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    $storage->capacity = $capacity;
    $storage->type = $type;
    $storage->id_product = $id;

    $storage->save();
    return $errors;
  }

  public function addOther($id){
    $errors = array();
    $other = new Other;

    if(count($errors) != 0)
      return $errors;

    $other->id_product = $id;

    $other->save();
    return $errors;
  }

  
  // Editing functions
  public function editCpu($baseFreq, $turboFreq, $socket, $threads, $cores, $id){
    $errors = array();
    $cpu = CPU::find($id);
    
    $regex = '/[0-9]+.?[0-9]+/';
    if(!preg_match($regex, $baseFreq) && !is_null($baseFreq))
      array_push($errors, 'CPU: Field 1 (Base Frequency) must be a float.');

    if(!preg_match($regex, $turboFreq) && !is_null($turboFreq))
      array_push($errors, 'CPU: Field 2 (Turbo Frequency) must be a float.');
    
    if(!is_numeric($threads) && !is_null($threads))
      array_push($errors, "CPU: Field 4 (Threads) must be an integer.");

    if(!is_numeric($cores) && !is_null($cores))
      array_push($errors, "CPU: Field 5 (Cores) must be an integer.");

    if(count($errors) != 0)
      return $errors;
    
    if (!is_null($baseFreq))  $cpu->basefreq = $baseFreq;
    if (!is_null($turboFreq)) $cpu->turbofreq = $turboFreq;
    if (!is_null($socket))    $cpu->socket = $socket;
    if (!is_null($threads))   $cpu->threads = $threads;
    if (!is_null($cores))     $cpu->cores = $cores;
    
    $cpu->save();
    return $errors;
  }
  
  public function editGpu($memory, $coreClock, $boostClock, $hdmiPorts, $displayPorts, $id){
    $errors = array();
    $gpu = GPU::find($id);

    if(!is_numeric($memory) || is_null($memory))
      array_push($errors, "GPU: Field 1 (Memory) must be an integer.");

    if(!is_numeric($coreClock) || is_null($coreClock))
      array_push($errors, "GPU: Field 2 (Core Clock) must be an integer.");

    if(!is_numeric($boostClock) || is_null($boostClock))
      array_push($errors, "GPU: Field 3 (Boost Clock) must be an integer.");

    if(!is_numeric($hdmiPorts) || is_null($hdmiPorts))
      array_push($errors, "GPU: Field 4 (HDMI Ports) must be an integer.");

    if(!is_numeric($displayPorts) || is_null($displayPorts))
      array_push($errors, "GPU: Field 5 (Display Ports) must be an integer.");

    if(count($errors) != 0)
      return $errors;
    
    if (!is_null($memory))       $gpu->memory = $memory;
    if (!is_null($coreClock))    $gpu->coreClock = $coreClock;
    if (!is_null($boostClock))   $gpu->boostclock = $boostClock;
    if (!is_null($hdmiPorts))    $gpu->hdmiports = $hdmiPorts;
    if (!is_null($displayPorts)) $gpu->displayports = $displayPorts;

    $gpu->save();
    return $errors;
  }
  
  public function editMotherboard($chipset, $type, $id){
    $errors = array();
    $motherboard = Motherboard::find($id);

    if(is_null($chipset))
      array_push($errors, "Motherboard: Field 1 (Chipset) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Motherboard: Field 2 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    if(!is_null($chipset)) $motherboard->chipset = $chipset;
    if(!is_null($type))    $motherboard->type = $type;

    $motherboard->save();
    return $errors;
  }

  public function editPcCase($color, $weight, $type, $id){
    $errors = array();
    $pcCase = PcCase::Find($id);

    if(is_null($color))
      array_push($errors, "Desktop Case: Field 1 (Color) can't be empty.");
    
    if(is_null($weight))
      array_push($errors, "Desktop Case: Field 2 (Weight) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Desktop Case: Field 3 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    if (!is_null($color))  $pcCase->color = $color;
    if (!is_null($weight)) $pcCase->weight = $weight;
    if (!is_null($type))   $pcCase->type = $type;

    $pcCase->save();
    return $errors;
  }
    
  public function editPowerSupply($wattage, $certification, $type, $id){
    $errors = array();
    $powerSupply = PowerSupply::find($id);
    
    if(is_null($wattage))
      array_push($errors, "Power Supply: Field 1 (Color) can't be empty.");
    
    if(is_null($certification))
      array_push($errors, "Power Supply: Field 2 (Certification) can't be empty.");
    
    if(is_null($type))
      array_push($errors, "Power Supply: Field 3 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;
    
    if(!is_null($wattage))       $powerSupply->wattage = $wattage;
    if(!is_null($certification)) $powerSupply->certification = $certification;
    if(!is_null($type))          $powerSupply->type = $type;

    $powerSupply->save();
    return $errors;
  }

  public function editCooler($type, $id){
    $errors = array();
    $cooler = Cooler::find($id);
    
    if(is_null($type))
      array_push($errors, "Cooler: Field 1 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    if (!is_null($type)) $cooler->type = $type;
    
    $cooler->save();
    return $errors;
  }

  public function editStorage($capacity, $type, $id){
    $errors = array();
    $storage = Storage::find($id);

    if(!is_numeric($capacity))
      array_push($errors, "Storage: Field 1 (Capacity) must be an integer.");
    
    if(is_null($type))
      array_push($errors, "Storage: Field 2 (Type) can't be empty.");

    if(count($errors) != 0)
      return $errors;

    if (!is_null($capacity)) $storage->capacity = $capacity;
    if (!is_null($type))     $storage->type = $type;
    
    $storage->save();
    return $errors;
  }

  public function editOther($id){
    $errors = array();
    $other = Other::find($id);

    if(count($errors) != 0)
      return $errors;

    $other->save();
    return $errors;
  }

  public function blockUser($id){
    $user = User::find($id);
    
    $user->isbanned = !$user->isbanned;
    $user->save();

    return redirect()->back();
  }

  public function searchProducts(Request $request){
    $admin = User::find(Auth::id());
    $wildcard = $request->input('search');

    $keyword1 = strtolower($wildcard); // Make a string uppercase
    $keyword2 = strtoupper($wildcard); // Make a string lowercase
    $keyword3 = ucfirst($wildcard);    // Make a string's first character uppercase
    $keyword4 = ucwords($wildcard);    // Uppercase the first character of each word in a string

    $queryName = Product::where('name', 'like', '%'.$wildcard.'%');
    $firstQuery = Product::where('name', 'like', '%'.$keyword1.'%');
    $secondQuery = Product::where('name', 'like', '%'.$keyword2.'%');
    $thirdQuery = Product::where('name', 'like', '%'.$keyword3.'%');
    $fourthQuery = Product::where('name', 'like', '%'.$keyword4.'%');

    $queryBrand = Product::where('brand', 'like', '%'.$wildcard.'%');
    $fifthQuery = Product::where('brand', 'like', '%'.$keyword1.'%');
    $sixthQuery = Product::where('brand', 'like', '%'.$keyword2.'%');
    $seventhQuery = Product::where('brand', 'like', '%'.$keyword3.'%');
    $eighthQuery = Product::where('brand', 'like', '%'.$keyword4.'%');

    $queryDescription = Product::where('description', 'like', '%'.$wildcard.'%');
    $ninthQuery = Product::where('description', 'like', '%'.$keyword2.'%');


    $results = $queryName
    ->union($queryBrand)
    ->union($queryDescription)
    ->union($firstQuery)
    ->union($secondQuery)
    ->union($thirdQuery)
    ->union($fourthQuery)
    ->union($fifthQuery)
    ->union($sixthQuery)
    ->union($seventhQuery)
    ->union($eighthQuery)
    ->union($ninthQuery)
    ->get();

    return view('pages.profile.user_profile', [
      'user' => $admin,
      'entries' => $results,
      'content' => 'partials.admin.list_products',
      'breadcrumbs' => [route('profile') => $admin->username],
      'current' => 'Products'
    ]);
  }

  public function searchUsers(Request $request){
    $admin = User::find(Auth::id());
    $wildcard = $request->input('search');

    $keyword1 = strtolower($wildcard); // Make a string uppercase
    $keyword2 = strtoupper($wildcard); // Make a string lowercase
    $keyword3 = ucfirst($wildcard);    // Make a string's first character uppercase
    $keyword4 = ucwords($wildcard);    // Uppercase the first character of each word in a string

    $queryUsername = User::where('username', 'like', '%'.$wildcard.'%');
    $firstQuery = User::where('username', 'like', '%'.$keyword1.'%');
    $secondQuery = User::where('username', 'like', '%'.$keyword2.'%');
    $thirdQuery = User::where('username', 'like', '%'.$keyword3.'%');
    $fourthQuery = User::where('username', 'like', '%'.$keyword4.'%');

    $queryEmail = User::where('email', 'like', '%'.$wildcard.'%');
    $fifthQuery = User::where('email', 'like', '%'.$keyword1.'%');
    $sixthQuery = User::where('email', 'like', '%'.$keyword2.'%');
    $seventhQuery = User::where('email', 'like', '%'.$keyword3.'%');
    $eighthQuery = User::where('email', 'like', '%'.$keyword4.'%');

    $queryPhone = User::where('phone', 'like', '%'.$wildcard.'%');

    $results = $queryUsername
    ->union($queryEmail)
    ->union($queryPhone)
    ->union($firstQuery)
    ->union($secondQuery)
    ->union($thirdQuery)
    ->union($fourthQuery)
    ->union($fifthQuery)
    ->union($sixthQuery)
    ->union($seventhQuery)
    ->union($eighthQuery)
    ->get();


    return view('pages.profile.user_profile', [
      'user' => $admin,
      'entries' => $results,
      'content' => 'partials.admin.list_users',
      'breadcrumbs' => [route('profile') => $admin->username],
      'current' => 'Users'
    ]);
  }
}
?>
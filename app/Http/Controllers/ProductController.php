<?php

namespace App\Http\Controllers;

use App\Models\CPU;
use App\Models\Product;
use App\Models\GPU;
use App\Models\Motherboard;
use App\Models\Cooler;
use App\Models\PowerSupply;
use App\Models\Storage;
use App\Models\PcCase;
use App\Models\Other;
use App\Models\Review;

use App\Models\Customer;
use App\Models\User;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{ 
  public function search(Request $request){
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

    return view('pages.products.products_list', 
    [
      'products' => $results,
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => 'Search'
    ]);
  }

  public function getAllProducts(){
    return view('pages.products.products_list', [
      'products' => Product::all(),
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => null
    ]);
  }

  public function getCategoryProducts($category){
    $products = Product::where('category', '=', $category)->get();
    /* dd($products); */
    return view('pages.products.products_list', 
    [
      'products' => $products,
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => $category
    ]);
  }
  
  public function showProduct($id){
    $product = Product::find($id);
    $entries = Review::where('id_product', '=', $id)->get();
    $details = null;

    if(Auth::guest())
      $user = null;

    else
      $user = User::find(Auth::user()->id);

    $reviews = array();
    foreach($entries as $entry){
      $customer = Customer::find($entry->id_customer);
      $user = User::find($customer->id_user);
      //dd($customer, $user);
      array_push($reviews, 
      [
        'user' => $user,
        'content' => $entry
      ]);
    }

    //dd($reviews);
    
    return view('pages.products.product', 
    [
      'user' => $user,
      'product' => $product,
      'details' => $details,
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => $product->name,
      'reviews' => $reviews
    ]);
  }

  public function upvote($id){
    $review = Review::find($id);
    $review->yesvotes++;
    $review->save();

    return redirect()->back();
  }

  public function downvote($id){
    $review = Review::find($id);
    $review->novotes++;
    $review->save();

    return redirect()->back();
  }

  public function post(Request $request, $user_id, $product_id){
    $customer = Customer::where('id_user', '=', Auth::id())->first();
    $review = new Review;

    //dd($request);

    $review->rating = $request->input('rating');
    $review->text = $request->input('comment');
    $review->id_customer = $customer->id;
    $review->id_product = $product_id;

    $review->save();

    return redirect()->back();
  }

  public function filter(Request $request){
    $stock = $request->input('stock');
    $price = $request->input('price');
    $rating = $request->input('rating');
    $orderBy = $request->input('orderBy');
    
    $char = '>';
    if($stock == "off")
      $char = '=';
    
    $table = "price";
    if ($orderBy == "none")
      $table = "id";
    else if ($orderBy == "stock-high-low" || $orderBy == "stock-low-high")
      $table = "stock";
    else if ($orderBy == "rating-high-low" || $orderBy == "rating-low-high")
      $table = "rating";

    $string = "asc";
    if ($orderBy == "price-high-low" || $orderBy == "rating-high-low" || $orderBy == "stock-high-low")
      $string = "desc";
    
    $results = Product::
    where('stock', $char, 0)
    ->where('price', '<=', $price)
    ->where('rating', '>=', $rating)
    ->orderBy($table, $string)
    ->get();
    
    return view('pages.products.products_list', 
    [
      'products' => $results,
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => 'Search'
    ]);
  }

  public function delete($review_id){
    $review = Review::find($review_id);
  
    $review->delete();

    return redirect()->back();
  }
  
  public function report($review_id){
    $admins = User::where('isadmin', '=', true)->get();

    foreach($admins as $admin){
      $notification = new Notification;
      $notification->content = "Someone has reported review #".$review_id.", go check on it";
      $notification->id_user = $admin->id;
      $notification->save();
    }

    return redirect()->back();
  }

  public function searchBrand($brand){
    //$product = Product::where('')
    
    $brandLower = strtolower($brand);
    $brandUpper = strtoupper($brand);
    $brandCamel = ucfirst($brand);

    $firstQuery = Product::where('brand', 'like', '%'.$brandLower.'%');
    $secondQuery = Product::where('brand', 'like', '%'.$brandUpper.'%');
    $thirdQuery = Product::where('brand', 'like', '%'.$brandCamel.'%');

    $results = $firstQuery
    ->union($secondQuery)
    ->union($thirdQuery)
    ->get();

    return view('pages.products.products_list', 
    [
      'products' => $results,
      'breadcrumbs' => [route('allProducts') => 'Products'],
      'current' => 'Search'
    ]);
  }
}
?>
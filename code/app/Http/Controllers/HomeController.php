<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartProduct;

class HomeController extends Controller
{
    public function home(){
        
        $products = Product::all();
        $productsList1 = array();
        $productsList2 = array();

        $inMostCarts = CartProduct::groupBy('id_product')
            ->selectRaw('count(*) as cnt, id_product')
            ->orderBy('cnt', 'desc')
            ->take(4)
            ->get();
        
        foreach($inMostCarts as $entry){
            array_push($productsList1, Product::find($entry->id_product)); 
        }
        
        for($i = 0; $i < 4; $i++){
            array_push($productsList2, $products[rand(0, count($products) - 1)]);
        }

        return view('pages.home', [
            'productsList1' => $productsList1,
            'productsList2' => $productsList2,
            'breadcrumbs' => [],
            'current' => null
        ]);
    }
}

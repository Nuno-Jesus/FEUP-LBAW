<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{ 
  public function about(){
    return view('pages.static.about', [
      'breadcrumbs' => [],
      'current' => 'About'
    ]);
  }

  public function faq(){
    return view('pages.static.faq', [
      'breadcrumbs' => [],
      'current' => 'FAQ'
    ]);
  }

  public function contacts(){
    return view('pages.static.contacts', [
      'breadcrumbs' => [],
      'current' => 'Contacts'
    ]);
  }
}

?>

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    public function __construct()
    {

    }

   public function ShowShops()
   {
        return view("shops.index");
   }
}

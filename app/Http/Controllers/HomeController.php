<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
	{
		
	}
	
	public function ShowIndex()
	{
		return view("base.page-base");
	}
	
	public function ShowLogin()
	{
		return view("base.login");
	}
	
	public function DoLogout()
	{
		Auth::logout();
		return redirect()->route("login")->with("toast-info", "Wylogowano pomyślnie.");
	}
	
	public function DoLogin(Request $request)
	{
		$credentials = [
			"username" => $request->input("username"),
			"password" => $request->input("password")
		];
        return Auth::attempt($credentials, $request->input("rememberme") == "true") ?
            redirect()->route("index")
                ->with("toast-info", "Witaj, " . Auth::user()->getUserName() . ". Zalogowałeś się pomyślnie! :)") :
            redirect()->back()->withInput()
                ->with("error", "Podano niepoprawną kombinację nazwy użytkownika i hasła.");
	}
}

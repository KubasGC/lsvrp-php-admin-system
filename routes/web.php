<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['oauth', 'web'])->group(function() {
	Route::get("/", "HomeController@ShowIndex")->name("index");
	// Route::get("logout", "HomeController@DoLogout")->name("logout");

	// Grupy
	Route::get("groups/{groupid?}", "GroupsController@ShowGroups")->where('groupid', '[0-9]+')->name("groups");

	Route::post("groups/{groupid}/saveData", "GroupsController@DoSaveData")->where('groupid', '[0-9]+')->name("groups.savedata");
    Route::post("groups/{groupid}/savePerms", "GroupsController@DoSavePerms")->where('groupid', '[0-9]+')->name("groups.saveperms");

    Route::post("ajax/group/add-order", "AjaxController@GroupAddOrder")->name("ajax.groupAddOrder");
    Route::post("ajax/group/save-orders/{groupid}", "AjaxController@GroupSaveOrders")->where('groupid', '[0-9]+')->name("ajax.groupSaveOrders");

    Route::get("ajax/group/get-orders/{groupid}", "AjaxController@GroupGetOrders")->where('groupid', '[0-9]+')->name("ajax.groupGetOrder");
    Route::get("ajax/group/delete-order/{orderid}", "AjaxController@GroupDeleteOrder")->where('orderid', '[0-9]+')->name("ajax.groupDeleteOrder");

	// Postacie
    Route::get("characters", "CharactersController@ShowSearch")->name("searchChar");
    Route::get("online-characters", "CharactersController@OnlineTab")->name("onlineChars");
    Route::post("ajax/searchChar", "AjaxController@SearchChar")->name("ajax.searchChar");
    Route::get("ajax/char/get-data/{charId}", "AjaxController@CharLoadData")->where('charId', '[0-9]+')->name("ajax.getCharData");
    Route::post("ajax/char/penalty", "AjaxController@GroupPenalty")->name("ajax.charPenalty");

    Route::get("characters/{id}", "CharactersController@ShowCharacter")->where('id', '[0-9]+')->name("showCharacter");

    // Sklepy
    Route::get("shops", "ShopsController@ShowShops")->name("shops.index");

    Route::get("ajax/shops/getData", "AjaxController@ShopsLoadData");

    Route::get("ajax/shops/remove-product/{productId}", "AjaxController@ShopRemoveProduct")->where('productId', '[0-9]+');
    Route::post("ajax/shops/add-product", "AjaxController@ShopAddProduct");

});
// Route::get("login", "HomeController@ShowLogin")->name("login");
// Route::post("login", "HomeController@DoLogin")->name("login.post");

Route::get("changes", "AjaxController@LastChanges");

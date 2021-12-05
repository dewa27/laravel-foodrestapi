<?php

use App\Models\Customer;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Cart;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/foods',function(){
    return Food::join('tb_food_category','tb_food_category.id_food_category','=','tb_food.id_food_category')->get();
});

Route::get('/foods/{id}',function($id){
    if(Food::find($id)){
        return Food::where('id_food',$id)->join('tb_food_category','tb_food_category.id_food_category','=','tb_food.id_food_category')->get();
    }else{
        return "Food not found!";
    }
})->where('id','[1-9]+(,[1-9]+)*');

Route::get('/foods/{food_name}',function($food_name){
    $food_name = isset($food_name) ? trim($food_name) : false;
    $query=Food::where('food_name', 'LIKE', '%'.$food_name. '%')->with('category')->get();
    return $query;
})->where('food_name', '^[a-zA-Z ]*$');

Route::get('/foods/category/{arr_id_category}',function($arr_id_category){
    $arr=explode(",",$arr_id_category);
    $query=Food::whereIn('id_food_category', $arr)->with('category')->get();
    return $query;
})->where('arr_id_category','[0-9]+(,[0-9]+)*');

Route::get('/foods/category/{arr_id_category}/{food_name}',function($arr_id_category,$food_name){
    $arr=explode(",",$arr_id_category);
    $query=Food::whereIn('id_food_category', $arr)->where('food_name', 'LIKE', '%'.$food_name. '%')->with('category')->get();
    return $query;
})->where('arr_id_category','[0-9]+(,[0-9]+)*')->where('food_name', '^[a-zA-Z ]*$');


Route::get('/carts/{id}',function($id){
    return Cart::where('id_customer',$id)->with('food');
});

Route::get('/customers',function(){
    return Customer::all();
});
Route::get('/customers/{id}',function($id){
    return Customer::find($id);
});
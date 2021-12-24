<?php

use App\Models\Customer;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Cart;
use App\Models\Payment;
// use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;

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

Route::post('/carts/create',function(Request $request){
    $cart = new Cart();
    $cart->id_food = $request->id_food;
    $cart->cart_qty = $request->cart_qty;
    $cart->id_customer = $request->id_customer;
    $cart->notes = $request->notes;
    if($cart->save()){
        return response()->json(['id_cart'=>$cart->id_cart,'code'=>200]);
    }
});

Route::post('/customers/create',function(Request $request){
    $customer=new Customer();
    $customer->customer_name=$request->customer_name;
    $customer->customer_qty=$request->customer_qty;
    $customer->status_makan=$request->status_makan;
    $customer->rekomendasi=$request->rekomendasi;
    $customer->uuid=Str::orderedUuid()->getHex();
    if($customer->save()){
        return $customer;
    }else{
        return 0;
    }
});

Route::post('/payment/create',function(Request $request){
    $payment=new Payment();
    $payment->code=$request->code;
    $payment->total=$request->total;
    $customer=Customer::where('uuid',$request->uuid)->get();
    $payment->id_customer=$customer[0]->id_customer;
    $payment->payment_status="waiting";
    $cartData=json_decode($request->cartData);
    // return $cartData;
    foreach($cartData as $cartItem){
        $cart=new Cart();
        $cart->id_food=$cartItem->food->id_food; 
        $cart->cart_qty=$cartItem->cart_qty;
        $cart->id_customer=$payment->id_customer;
        $cart->notes=$cartItem->notes;
        $cart->save();
    }
    if($payment->save()){
        return $payment;
    }else{
        return 0;
    }
    // return $request->cartData;
});

Route::post('/payment/check',function(Request $request){
    $payment=Payment::where('code',$request->code)->get();
    if($payment[0]->payment_status=="scanned"){
        $payment[0]->payment_status="paid";
        $payment[0]->save();
        return array('status'=>"success",'id_payment'=>$payment[0]->id_payment,'notes'=>"Berhasil discan");
    }else{
        return array('status'=>"error",'id_payment'=>$payment[0]->id_payment,'notes'=>"Gagal discan");
    }
});
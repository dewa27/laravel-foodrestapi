<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    // use HasFactory;
    protected $table="tb_food";
    protected $primaryKey="id_food";
    protected $guarded=[];
    public function cart()
    {
        return $this->belongsTo(Cart::class,'id_cart');
    }
    public function category(){
        return $this->belongsTo(FoodCategory::class,'id_food_category');
    }
}

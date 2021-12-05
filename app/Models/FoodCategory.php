<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{    
    protected $table="tb_food_category";
    protected $primaryKey="id_food_category";
    protected $guarded=[];
    // use HasFactory;
    public function foods(){
        return $this->hasMany(Food::class,"id_food");
    }
}

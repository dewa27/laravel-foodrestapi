<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table="tb_cart";
    protected $primaryKey="id_cart";
    protected $guarded=[];
    public function customer()
    {
        return $this->belongsTo(Customer::class,'id_customer');
    }
    public function food()
    {
        return $this->belongsTo(Food::class,"id_food");
    }
    // use HasFactory;
}

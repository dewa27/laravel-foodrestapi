<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // use HasFactory;
    protected $table="tb_customer";
    protected $primaryKey="id_customer";
    protected $guarded=[];
    public function cart()
    {
        return $this->hasMany(Cart::class,"id_cart");
    }
}

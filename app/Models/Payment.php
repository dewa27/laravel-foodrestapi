<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table="tb_payment";
    protected $primaryKey="id_payment";
    protected $guarded=[];
    public function customer()
    {
        return $this->belongsTo(Customer::class,'id_customer');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
public function user() { return $this->belongsTo(User::class); }
public function details() { return $this->hasMany(OrderDetail::class); }

}

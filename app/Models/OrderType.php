<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "order_type";

    protected $fillable = ["code", "description"];

    protected $hidden = ["created_at"];
}

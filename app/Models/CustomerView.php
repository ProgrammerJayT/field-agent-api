<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerView extends Model
{
    use HasFactory;

    protected $primaryKey = "customer_view_id";

    protected $guarded = [];
}

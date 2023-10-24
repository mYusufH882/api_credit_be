<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $table = 'credit';

    protected $fillable = [
        'user_id', 'credit_type', 'name', 'total_transaction', 'tenor', 'total_credit', 'status'
    ];
}

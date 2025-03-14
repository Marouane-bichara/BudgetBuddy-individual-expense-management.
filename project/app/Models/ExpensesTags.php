<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesTags extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_id',
        'tag_id'
    ];


    
}

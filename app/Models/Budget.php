<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $primaryKey = 'budget_id';
    protected $fillable = [
        'template_id',
        'user_id',
        'group_id',
        'start_date',
        'end_date',
    ];
}
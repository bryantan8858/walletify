<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'category_name',
    ];
    
    public function pac()
    {
        return $this->hasMany(PartAllocationCategory::class, 'category_id');
    }
}

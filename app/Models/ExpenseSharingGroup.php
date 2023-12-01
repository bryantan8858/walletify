<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseSharingGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'description'];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }
}

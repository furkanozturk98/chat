<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'group_id',
        'status'
    ];
}

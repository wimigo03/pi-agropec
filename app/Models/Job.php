<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Job extends Model
{
    use NodeTrait;

    protected $fillable = [
        'title',
        'parent_id'
    ];
}

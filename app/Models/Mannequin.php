<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mannequin extends Model
{
    protected $table = 'mannequins';

    protected $fillable = [
        'po',
        'itemref',
        'company',
        'category',
        'type',
        'price',
        'description',
        'images',
        'file',
        'pdf',
        'addedBy',
        'activeStatus',
    ];

    
}

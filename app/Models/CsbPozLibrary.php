<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsbPozLibrary extends Model
{
    use HasFactory;

    protected $fillable = [
        'poz_number',
        'title',
        'unit',
        'unit_price',
        'year',
        'work_group',
        'description',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'year' => 'integer',
    ];
}

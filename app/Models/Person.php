<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';
    protected $fillable = [
        'cpf',
        'birth',
        'educational_level',
        'occupation_area',
        'number',
        'user_id'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;


    protected $fillable = [

        'group_id',
        'student_id',
        'count',
        'price',

    ];
    protected $hidden = [

        'created_at',
        'updated_at',
    ];
}

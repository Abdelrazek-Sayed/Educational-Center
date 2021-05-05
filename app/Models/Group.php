<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'body', 'image', 'teacher_id','created_by'];

    protected $hidden  = ['created_at','updated_at','created_by'];

    public function students()
    {
        return $this->hasMany(StudentGroup::class,'group_id','id');
    }
}

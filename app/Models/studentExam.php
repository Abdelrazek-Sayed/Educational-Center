<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentExam extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','exam_id','total_degree'];
    protected $hidden = ['created_at','updated_at'];
}

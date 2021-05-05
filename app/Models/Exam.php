<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start', 'end', 'time', 'degree', 'exam_type_id', 'group_id', 'teacher_id','close','count'];
    protected $hidden = ['created_at', 'updated_at'];


    public function studentGroups()
    {
        return $this->hasOne(StudentGroup::class,'group_id','group_id');
    }
    public function examType()
    {
        return $this->belongsTo(ExamType::class);
        // return $this->belongsTo(ExamType::class,'id','exam_type_id');
    }
}

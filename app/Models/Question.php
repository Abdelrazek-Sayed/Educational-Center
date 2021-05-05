<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'exam_id'];
    protected $hidden = ['created_at', 'updated_at','exam_id'];

    public function questionImage()
    {
        return $this->hasOne(questionImage::class,'question_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_session extends Model
{
    use HasFactory;


    public function groupSesssion()
    {
      return $this->belongsTo(Group::class,'group_id','id');
    }


    
    // public function groups()
    // {
      //   return $this->belongsTo(Group::class);
    // }

    protected $fillable =['name','link','group_id','from','to'];
}

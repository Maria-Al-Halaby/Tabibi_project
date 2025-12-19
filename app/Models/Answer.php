<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        "answer_content" , 
        "patient_id"
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function question()
    { 
        return $this->belongsTo(Question::class); 
    }

}

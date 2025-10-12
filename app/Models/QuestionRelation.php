<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionRelation extends Model
{
    protected $fillable = [
        "paraent_question_id" , 
        "child_question_id" , 
        "targget_answer_value"
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

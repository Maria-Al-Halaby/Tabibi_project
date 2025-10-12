<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\Node\Specificity;

class Question extends Model
{
    protected $fillable = [
        "content" , 
        "specializtion_id" , 
        "is_root"
    ];

    public function specialization()  {
        return $this->belongsTo(Specificity::class);
    }

    public function questionRelation()  {
        return $this->hasMany(QuestionRelation::class);
    }
}

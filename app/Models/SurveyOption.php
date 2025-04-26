<?php

namespace App\Models;

use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    protected $fillable = [
        'survey_question_id',
        'option_text',
    ];

    public function survey_question(){
        return $this->belongsTo(SurveyQuestion::class);
    }
}

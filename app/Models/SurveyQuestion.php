<?php

namespace App\Models;

use App\Models\Survey;
use App\Models\SurveyOption;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'survey_id',
        'question',
        'type',
    ];

    public function survey(){
        return $this->belongsTo(Survey::class);
    }

    public function survey_optios(){
        return $this->hasMany(SurveyOption::class);
    }
}

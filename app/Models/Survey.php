<?php

namespace App\Models;

use App\Models\Poll;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'title',
        'expiry_date'
    ];
 
    public function polls(){
        return $this->hasMany(Poll::class,'survey_title_id');
    }

    public function survey_questions(){
        return $this->hasMany(SurveyQuestion::class);
    }
}

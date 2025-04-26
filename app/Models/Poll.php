<?php

namespace App\Models;

use App\Models\PollOption;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'heading',
        'survey_title_id',
        'is_multi_select',
    ];

    public function survey(){
        return $this->belongsTo(Survey::class,'survey_title_id');
    }
    public function poll_options(){
        return $this->hasMany(PollOption::class);
    }
}

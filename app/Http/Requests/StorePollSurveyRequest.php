<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePollSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       
        $common_rules = [
            'typeSelect' => 'required|in:poll,survey',
            'heading' => 'required|string|max:255',
        ];

        if($this->typeSelect == 'poll'){
            $extra_rules = [
                'pollOptions' => 'required|in:single,multi',
                'optionText' => 'required|array',
                'optionText.*' => 'required|string|max:255',
                'expiry_date' => 'required|date|after_or_equal:today',
            ];
        }
        else{
            $extra_rules = [
                'title' => 'required|string|max:255',
                'surveyQuestion' => 'required|array',
                'surveyQuestion.*' => 'required|string|max:255',
                'questionType' => 'required|array',
                'questionType.*' => 'required|string|max:255',
                'expiry_date' => 'required|date|after_or_equal:today',
                'pollOptions' => 'required|in:single,multi',
                'optionText' => 'required|array',
                'optionText.*' => 'required|string|max:255' 
            ];
        }

        return array_merge($common_rules, $extra_rules);

    }
}

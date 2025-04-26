<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPollSurveyRequest;
use App\Http\Requests\StorePollSurveyRequest;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Survey;
use App\Models\SurveyOption;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class SurveyController extends Controller
{
    public function store(StorePollSurveyRequest $request)
    {
        try{
            $data = $request->validated();
    
            if($data['typeSelect'] == 'survey'){

               $survey = Survey::create([
                    'title' => $data['title'],
                    'expiry_date' => $data['expiry_date'],
                ]);
                $is_multi_select = $data['pollOptions'] == "single" ? false : true; 
                $poll = Poll::create([
                    'heading' => $data['heading'],
                    'survey_title_id' => $survey->id,
                    'is_multi_select' => $is_multi_select,
                ]);

                //create poll options 
                foreach($data['optionText'] as $option)
                {
                    PollOption::create([
                         'poll_id' => $poll->id,
                         'option_text' => $option
                    ]);
                }

                //create survey questions 

                foreach ($data['surveyQuestion'] as $index => $ques) {
                    $question = SurveyQuestion::create([
                        'survey_id' => $survey->id,
                        'question' => $ques,
                        'type' => $data['questionType'][$index]
                    ]);
                }
                toastr()->success("Survey has been created");
            }else{
                $survey = Survey::create([
                    'expiry_date' => $data['expiry_date'],
                ]);

                $is_multi_select = $data['pollOptions'] == 'single' ? false : true;
                $poll = Poll::create([
                    'survey_title_id' => $survey->id,
                    'heading' => $data['heading'],
                    'is_multi_select' => $is_multi_select,
                ]);
                foreach($data['optionText'] as $option){
                    $poll_option = PollOption::create([
                        'poll_id' => $poll->id,
                        'option_text' => $option 
                    ]);
                }               
                toastr()->success("Poll has been created");
            }
                return redirect()->route('form');
        }catch(ValidationException $e){
            return redirect()->back()->withErrors($e->errors())->withInput();
        }catch(Throwable $th){
            Log::error("Server Problem! Try Again Later ",['error'=>$th->getMessage()]);
            toastr()->error("Create Poll/Survey Request Failed!");
            return back();
        }
    }

    // show survey 
    public function show(Survey $survey){
        $survey = Survey::with('survey_questions','polls')->where('id',$survey->id)->first();
        return view('view-survey',compact('survey'));
    }

    public function edit(Survey $survey){
        $survey = Survey::with('survey_questions','polls')->where('id', $survey->id)->first();
        return  view('edit-survey', compact('survey'));
    }
    // public function deleteQuestion($id){
    //     $question = SurveyQuestion::find($id);
    //     if(!$question){
    //         toastr()->error("Question not found");
    //         return response()->json(['success' => false, 'message' => 'Question not found']);
    //     }
    //     $question->delete();
    //     toastr()->success("Question deleted successfully");
    //     return response()->json(['success' => true, 'message' => 'Question deleted successfully']);
    // }
    public function update(EditPollSurveyRequest $request, Survey $survey)
    {
        // dd(json_decode($request->deletedQuestions, true));
        // die;
        $validate_data=$request->validated();   
        // return $validate_data;
        $survey->update([
            'title' => $validate_data['title'],
            'expiry_date' => $validate_data['expiry_date'],
        ]);

        $my_poll = Poll::find($survey->id);
        $is_multi_select = $validate_data['pollOptions'] == 'single' ? false : true; 
        $my_poll->update([
            'heading' => $validate_data['heading'],
            'is_multi_select' => $is_multi_select,
        ]);
        
        if($request->deletedOptions){
            $deleted_options = json_decode($request->deletedOptions, true);
            PollOption::whereIn('id', $deleted_options)->delete();
        }

        PollOption::where('poll_id', $my_poll->id)->delete();
        
        foreach($validate_data['optionText'] as $option){
           $poll_option = PollOption::create([
                'poll_id' => $my_poll->id,
                'option_text' => $option 
            ]);
        }

        if($request->deletedQuestions){
            $deleted_questions = json_decode($request->deletedQuestions, true);
            SurveyQuestion::whereIn('id', $deleted_questions)->delete();
        }

        SurveyQuestion::where('survey_id', $survey->id)->delete();

        foreach($validate_data['surveyQuestion']  as $index => $ques){
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => $ques,
                'type' => $validate_data['questionType'][$index]
            ]);
        }

        toastr()->success("Survey Updated Successfully");
        return redirect()->back();

    }
}

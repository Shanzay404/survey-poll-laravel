<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPollSurveyRequest;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function show(Poll $poll)
    {
        $poll = Poll::with('poll_options','survey')->where('id',$poll->id)->first();
        return view('view-poll',compact('poll'));
    }
    public function edit(Poll $poll)         //ok
    {
        $poll = Poll::with('poll_options','survey')->where('id',$poll->id)->first();
        return view('edit-poll',compact('poll'));
    }
    public function update(EditPollSurveyRequest $request, Poll $poll)    
    {
        $validated_data = $request->validated();
        $myPoll = Poll::with('poll_options')->where('id',  $poll->id)->first();
        $is_multi_select = $validated_data['pollOptions'] == 'single' ? false : true;

        $myPoll->update([
            'title' => $validated_data['heading'],
            'is_multi_select' => $is_multi_select,
        ]);

        // deleteOptions
        if($request->deletedOptions){
            $deletedOptions = json_decode($request->deletedOptions, true);
            PollOption::whereIn('id', $deletedOptions)->delete(); 
        }

        PollOption::where('poll_id',$myPoll->id)->delete(); 

        
        foreach($validated_data['optionText'] as $option){
            $poll_option = PollOption::create([
                'poll_id' => $myPoll->id,
                'option_text' => $option 
            ]);
        }  
        toastr()->success("Poll Updated Successfully");
        return redirect()->back();
    }

    
    // public function deleteOption($id)    //ok
    // {
    //     $option = PollOption::find($id);
    //     if (!$option) {
    //         toastr()->error("Option not found");
    //         return response()->json(['success' => false, 'message' => 'Option not found'], 404);
    //     }
    
    //     $option->delete();
    
    //     toastr()->success("Option removed Successfully");
    //     return response()->json(['success' => true, 'message' => 'Option deleted successfully']);
    // }


    



    public function updateOptionOrder(Request $request)
    {
        $orderData = $request->order;
        // return $orderData;
        foreach ($orderData as $item) {
            // PollOption::where('id', $item['id'])->update(['id' => $item['id']]);
            return $item['id'];
        }

        return response()->json(['success' => true, 'message' => 'Option order updated successfully!']);
    }
}

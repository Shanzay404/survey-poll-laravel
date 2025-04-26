@extends('layout.layout')

@section('content')

    <form class="shadow w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" action="{{ route('form.submit') }}" method="POST">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">View Survey</h2>


        <label class="block font-medium text-gray-700 mb-2">Survey Title</label>

        <input type="text"  value="{{ $survey->title }}" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
              
        <label class="block font-medium text-gray-700 mb-2">Expiry Date</label>

        <input type="text"  value="{{ $survey->expiry_date }}" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
     


        <h2 class="font-bold mb-3 text-indigo-600" id="poll_heading_text">Polls</h2>

        @foreach ($survey->polls as $poll)
            
        <label class="block font-medium text-gray-700 mb-2">Poll Heading</label>

        <input type="text"  value="{{ $poll->heading }}" class=" p-3 border border-gray-300 rounded  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold mb-4 focus:ring-2 focus:ring-indigo-500">

        <label class="block font-medium text-gray-700 mb-2">Poll Options</label>

        {{-- @foreach ($poll->poll_options as $options) --}}

        <div id="optionContainer" class="">
            @if ($poll->poll_options->count() > 0)
                @foreach ($poll->poll_options as $option)
                    <div id="option-id-{{ $option->id }}" class="mb-2 option_item border border-gray-300 px-4 py-3  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold" draggable="true">
                        {{ $option->option_text }}
                    </div>
                @endforeach
            @else
                <p class="col-span-5 text-gray-500">No options Available</p>
            @endif
        </div>

        {{-- @endforeach --}}
       
        @endforeach
        

        <label class="block font-medium text-gray-700 mb-2 mt-4">Survey Questions</label>

    
        <div id="questionsList" class="mb-4 flex flex-col gap-2">
            @foreach ($survey->survey_questions as $ques)
                <div class="p-3 bg-gray-100 rounded">
                    <input type="text" value="{{ $ques->question }}" class="shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Question 1">
                    <input type="text" value="{{ $ques->type }}" class="mt-2 shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Question 1">
                </div>
            @endforeach
        </div>

       


    </form>
@endsection
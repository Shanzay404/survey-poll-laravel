@extends('layout.layout')

@section('content')

    <form class="shadow w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" action="{{ route('survey.update', $survey->id) }}" method="POST">
        @csrf
        @method('PUT')

        <select name="typeSelect" class="hidden" id="typeSelect" class="mb-4 w-full p-2 border rounded">
            <option value="survey" selected >Survey</option>
        </select>
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Edit Survey</h2>
        <input type="hidden" name="deletedOptions" id="deletedOptions">
        <input type="hidden" name="deletedQuestions" id="deletedQuestions">        



        
        <label class="block font-medium text-gray-700 mb-2">Survey Title</label>
        <input type="text"  value="{{ $survey->title }}" name="title" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('title')
            {{ $message }}
        @enderror
        </small>
              
        
        <label class="block font-medium text-gray-700 mb-2">Expiry Date</label>
        <input type="date"  value="{{ $survey->expiry_date }}" name="expiry_date" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('expiry_date')
            {{ $message }}
        @enderror
        </small>
     

        <div class="polls mb-5">

            
            <h2 class="font-bold mb-3 text-indigo-600" id="poll_heading_text">Polls</h2>

            @foreach ($survey->polls as $poll)
                
                <label class="block font-medium text-gray-700 mb-2">Poll Heading</label>

                <input type="text"  value="{{ $poll->heading }}" name="heading" class=" p-3 border border-gray-300 rounded  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold mb-4 focus:ring-2 focus:ring-indigo-500">
                <small class="text-red-600">
                    @error('heading')
                        {{ $message }}
                    @enderror
                </small>

                <label class="block font-medium text-gray-700 mb-2">Poll Type</label>
                <select name="pollOptions" class="shadow w-full p-3 border border-gray-300 rounded mb-4 focus:ring-2 focus:ring-indigo-500">
    
                    <option value="single" {{ $poll->is_multi_select == 0 ? 'selected' : ' ' }} >Single Select</option>
                    <option value="multi" {{ $poll->is_multi_select == 1 ? 'selected' : ' ' }} >Multi Select</option>
    
                </select>
                <label class="block font-medium text-gray-700 mb-2">Poll Options</label>

                <div id="optionsList">

                    @if ($poll->poll_options->count() > 0)
                        @foreach ($poll->poll_options as $option)
                        <div class="option-item mb-2  shadow flex border rounded py-2 px-4 items-center w-full">
                            <input type="text" name="optionText[]" id="option-id-{{ $option->id }}" value="{{ $option->option_text }}" class="font-semibold w-full" draggable="true">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteOption({{ $option->id }}, this)">
                                <i data-lucide="x" class="w-5 h-5"></i> 
                            </button>
                        </div>
                        @endforeach
                        @error('optionText.0')
                        <small class="text-red-600">
                                {{ $message }}
                        </small>
                        @enderror
                        
                    @else
                       
                        <input type="text" name="optionText[]" value="{{ old('optionText.0') }}" class="shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Option 1">
                            
                    @endif
                    
                </div>
                <p id="remainingOptionerrorMessage" class="text-red-500 mt-2 hidden">At least one option is required!</p>


            <p id="errorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>

            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mt-2" onclick="addOption(event)">Add Option</button>

        
            @endforeach
        

        </div>
       
        <div id="surveyQuestions" class="">
            <label class="block font-medium text-gray-700 mb-2">Survey Questions</label>
            <div id="questionsList" class="mb-4 flex flex-col gap-2">
                @foreach ($survey->survey_questions as $question)
                <div class="question pb-3 px-3 bg-gray-100 rounded relative" style="padding-top: 40px !important;">
                    <input type="text" value="{{ $question->question }}" name="surveyQuestion[]" class=" shadoew w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Question 1">
                    @error('surveyQuestion.0')
                    <small class="text-red-600">
                            {{ $message }}
                    </small>
                    @enderror


                    <select name="questionType[]" class="w-full p-2 border rounded mt-2" onchange="toggleOptions(this)">
                        <option value="text" {{ $question->type == 'text' ? 'selected' : ''  }}>Open-ended</option>
                        <option value="single_select" {{ $question->type == 'single_select' ? 'selected' : ''  }}>Single Select</option>
                        <option value="multi_select" {{ $question->type == 'multi_select' ? 'selected' : ''  }}>Multi Select</option>
                    </select>
                    
                    
                    <div class="optionsContainer hidden mt-2"></div>
                    
                    
                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="deleteQuestion({{ $question->id }}, this)">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                @endforeach
                
            </div>
            <p id="remainingQuestionErrorMessage" class="text-red-500 mt-2 hidden">At least one Question is required!</p>
            <p id="SurveyErrorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition" onclick="addQuestion(event)">Add Question</button>
        </div>
        
        <button class="mt-4 bg-indigo-600 text-white px-6 py-3 rounded w-full text-lg hover:bg-indigo-700 transition" type="submit">Update</button>
    </form>
    @include('script')
    <script>
           let deletedQuestions = [];
           let deletedOptions = [];
           function deleteOption(optionId, buttonElement) {
            if (!confirm("Are you sure you want to delete this option?")) return;

            buttonElement.closest('.option-item').remove();

            if (!deletedOptions.includes(optionId)) {
                deletedOptions.push(optionId);
            }

            document.getElementById("deletedOptions").value = JSON.stringify(deletedOptions);
            console.log(deletedOptions);
            checkRemainingOption();

        }
        function checkRemainingOption(){
            let remaining_options = document.querySelectorAll(".option-item");
            if(remaining_options.length === 0 ){
                document.getElementById("remainingOptionerrorMessage").classList.remove('hidden');
            }else{
                document.getElementById("remainingOptionerrorMessage").classList.add('hidden');
            }
        }


        // deletedQuestions
        function deleteQuestion(questionId, buttonElement) {
            if (!confirm("Are you sure you want to delete this Question?")) return;

            buttonElement.closest('.question').remove();

            if (!deletedQuestions.includes(questionId)) {
                deletedQuestions.push(questionId);
            }

            document.getElementById("deletedQuestions").value = JSON.stringify(deletedQuestions);
            console.log(deletedQuestions);
            checkRemainingQuestion();

        }
        function checkRemainingQuestion(){
            let remaining_questions = document.querySelectorAll(".question");
            if(remaining_questions.length === 0 ){
                document.getElementById("remainingQuestionErrorMessage").classList.remove('hidden');
            }else{
                document.getElementById("remainingQuestionErrorMessage").classList.add('hidden');
            }
        }
    </script>
@endsection
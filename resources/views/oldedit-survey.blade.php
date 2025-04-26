@extends('layout.layout')

    @section('content')
         
    <form class="w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" action="{{ route('survey.update', $survey->id) }}" method="POST">
        @csrf
        @method('PUT')
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Edit Survey</h2>
        
        
        <select name="typeSelect" class="hidden" id="typeSelect" class="mb-4 w-full p-2 border rounded">
            <option value="survey" selected >Survey</option>
        </select>
        
        <label class="block font-medium text-gray-700 mb-2">Title</label>
        <input type="text" placeholder="Enter Title" value="{{ $survey->title }}" name="title" class="shadow  w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('title')
            {{ $message }}
        @enderror
        </small>
        <label class="block font-medium text-gray-700 mb-2">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ $survey->expiry_date }}" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('expiry_date')
            {{ $message }}
        @enderror
        </small>
        
        
        <div id="surveyQuestions" class="hidden">
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
            <p id="SurveyErrorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition" onclick="addQuestion(event)">Add Question</button>
        </div>
        




        {{-- <div id="surveyQuestions" class="hidden">
            <label class="block font-medium text-gray-700 mb-2">Survey Questions</label>

            
            <select name="questionType[]" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500" onchange="toggleOptions(this)">
                <option value="text" {{ $survey->survey_questions->type == 'text' ? 'selected' : ''  }}>Open-ended</option>
                <option value="single_select" {{ $survey->survey_questions->type == 'single_select' ? 'selected' : ''  }}>Single Select</option>
                <option value="multi_select" {{ $survey->survey_questions->type == 'multi_select' ? 'selected' : ''  }}>Multi Select</option>
            </select> --}}




            {{-- <select name="pollOptions" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">

                @if ($poll->is_multi_select == 0)
                    <option value="single" selected>Single Select</option>
                    <option value="multi">Multi Select</option>
                @else
                <option value="single">Single Select</option>
                <option value="multi" selected>Multi Select</option>
                @endif
            </select> --}}
            {{-- <div id="optionsList" class="mb-4 flex flex-wrap gap-2" >
              
                    @if ($poll->poll_options->count() > 0)
                    
                    @foreach ($poll->poll_options as $options)
                        <div class="option-item shadow flex border rounded py-1 px-2 items-center w-[31%]">
                        <input type="text" name="optionText[]" value="{{ $options->option_text }}" class=" w-full p-2  focus:ring-2 focus:ring-blue-500" placeholder="Option 1">
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteOption({{ $options->id }}, this)">
                            ❌ 
                        </button>
                        </div>
                        <br>
                    @endforeach

                    @else
                    
                    <input type="text" name="optionText[]" value="{{ old('optionText.0') }}" class="shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Option 1">
                        
                    @endif

                                       
                </div>
                @error('optionText.0')
                <small class="text-red-600">
                        {{ $message }}
                </small>
                @enderror
            </div> --}}

            {{-- <p id="errorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>

            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mt-2" onclick="addOption(event)">Add Option</button> --}}

        </div>
        
       
        
        <button class="mt-4 bg-indigo-600 text-white px-6 py-3 rounded w-full text-lg hover:bg-indigo-700 transition" type="submit">Update</button>
    </form>

    @include('script')

    <script>
        function deleteQuestion(questionId, buttonElement){
            if(!confirm("Are you sure you want to delete this question? ", questionId)) return;
            // console.log(questionId);
            

            fetch(`/survey/delete-question/${questionId}`, {
                method : "DELETE",
                headers :  {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ _method: "DELETE" }) // Laravel needs this
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the option from UI
                    buttonElement.closest('.question').remove();
                    window.location.reload(true);
                } else {
                    alert("Failed to delete question!");
                }
            })
            .catch(error => console.error("Error deleting Question:", error));
        }


    </script>
        @endsection
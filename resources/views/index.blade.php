@extends('layout.layout')

    @section('content')
         
    <form class="w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" action="{{ route('form.submit') }}" method="POST">
        @csrf
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Create Poll / Survey</h2>
        
        <label class="block font-medium text-gray-700 mb-2">Select Type</label>


        <select name="typeSelect" id="typeSelect" class=" shadow mb-4 w-full p-2 border rounded">
            <option value="poll" {{ old('typeSelect') == 'poll' ? 'selected' : '' }}>Poll</option>
            <option value="survey" {{ old('typeSelect') == 'survey' ? 'selected' : '' }}>Survey</option>
        </select>
        <div  id="survey_title">
            <label class="block font-medium text-gray-700 mb-2">Survey Title</label>
            <input type="text" placeholder="Enter Title" value="{{ old('title') }}" name="title" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
            <small class="text-red-600">
                @error('title')
                {{ $message }}
            @enderror
            </small>
        </div>
        <label class="block font-medium text-gray-700 mb-2">Survey Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('expiry_date')
            {{ $message }}
        @enderror
        </small>

        <h2 class="font-bold mb-3 text-indigo-600" id="poll_heading_text">Create Poll</h2>

        <label class="block font-medium text-gray-700 mb-2" id="poll_heading">Poll Heading</label>
        <input type="text" placeholder="Enter Heading" value="{{ old('heading') }}" name="heading" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('heading')
            {{ $message }}
        @enderror
        </small>
        <div id="pollOptions" class="hidden">
            <label class="block font-medium text-gray-700 mb-2">Poll Options</label>
            <select name="pollOptions" class="shadow  w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
                <option value="single">Single Select</option>
                <option value="multi">Multi Select</option>
            </select>
            <div id="optionsList" class="mb-4 flex flex-col gap-2 " >
                <div class="flex items-center gap-2">
                    <input type="text" name="optionText[]" value="{{ old('optionText.0') }}" class=" shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Option 1">
                    <br>
                </div>
                @error('optionText.0')
                <small class="text-red-600">
                        {{ $message }}
                </small>
                @enderror
            </div>

            <p id="errorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>

            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mt-2" onclick="addOption(event)">Add Option</button>

        </div>
        

        {{-- survey --}}
        <div id="surveyQuestions" class="hidden">
            <label class="block font-medium text-gray-700 mb-2">Survey Questions</label>
            <div id="questionsList" class="mb-4 flex flex-col gap-2">
                <div class="p-3 bg-gray-100 rounded">
                    <input type="text" name="surveyQuestion[]" class=" shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Question 1">
                    @error('surveyQuestion.0')
                    <small class="text-red-600">
                            {{ $message }}
                    </small>
                    @enderror



                    <select name="questionType[]" class="w-full p-2 border rounded mt-2" onchange="toggleOptions(this)">
                        <option value="text">Open-ended</option>
                        <option value="single_select">Single Select</option>
                        <option value="multi_select">Multi Select</option>
                    </select>


                    <div class="optionsContainer hidden mt-2"></div>




                </div>
            </div>
            <p id="SurveyErrorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition" onclick="addQuestion(event)">Add Question</button>
        </div>
        

        
        <button class="mt-4 bg-indigo-600 text-white px-6 py-3 rounded w-full text-lg hover:bg-indigo-700 transition" type="submit">Save & Publish</button>
    </form>

    @include('script')
@endsection

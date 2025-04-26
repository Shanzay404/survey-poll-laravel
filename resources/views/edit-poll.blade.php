@extends('layout.layout')

@section('content')
         
    <form class="w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" action="{{ route('poll.update', $poll->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="deletedOptions" id="deletedOptions">
       <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Edit Poll</h2>
        
        
        <select name="typeSelect" class="hidden" id="typeSelect" class="mb-4 w-full p-2 border rounded">
            <option value="poll" selected >Poll</option>
        </select>
        
        <label class="block font-medium text-gray-700 mb-2">Heading</label>
        <input type="text" placeholder="Enter Title" value="{{ $poll->heading }}" name="heading" class="shadow  w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('heading')
            {{ $message }}
        @enderror
        </small>
        <label class="block font-medium text-gray-700 mb-2">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ $poll->survey->expiry_date }}" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">
        <small class="text-red-600">
            @error('expiry_date')
            {{ $message }}
        @enderror
        </small>
        
        <div id="pollOptions" class="hidden">
            <label class="block font-medium text-gray-700 mb-2">Poll Options</label>
            <select name="pollOptions" class="shadow w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-indigo-500">

                <option value="single" {{ $poll->is_multi_select == 0 ? 'selected' : ' ' }} >Single Select</option>
                <option value="multi" {{ $poll->is_multi_select == 1 ? 'selected' : ' ' }} >Multi Select</option>

            </select>
            <div id="optionsList" class="mb-4 flex flex-wrap gap-2" >
                    @if ($poll->poll_options->count() > 0)
                    
                    @foreach ($poll->poll_options as $options)
                        <div class="option-item shadow flex border rounded py-1 px-2 items-center w-full">
                            <input type="text" name="optionText[]" value="{{ $options->option_text }}" class=" w-full p-2 " placeholder="Option 1">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteOption({{ $options->id }}, this)">
                                <i data-lucide="x" class="w-5 h-5"></i> 
                            </button>
                        </div>
                    @endforeach
                        
                    @else
                        
                        <input type="text" name="optionText[]" value="{{ old('optionText.0') }}" class="shadow w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Option 1">
                        
                    @endif

                    
                    
                </div>
                <p id="remainingOptionerrorMessage" class="text-red-500 mt-2 hidden">At least one option is required!</p>
                @error('optionText.0')
                <small class="text-red-600">
                        {{ $message }}
                </small>
                @enderror
            </div>

            <p id="errorMessage" class="text-red-500 mt-2 hidden">You can add up to 10 options only.</p>

            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mt-2" onclick="addOption(event)">Add Option</button>

        </div>
        
       
        
        <button class="mt-4 bg-indigo-600 text-white px-6 py-3 rounded w-full text-lg hover:bg-indigo-700 transition" type="submit">Update</button>
    </form>

    @include('script')

    <script>
      

        let deletedOptions = [];
     
        
        function deleteOption(optionId, buttonElement) {
            if (!confirm("Are you sure you want to delete this option?")) return;

            buttonElement.closest('.option-item').remove();

            if (!deletedOptions.includes(optionId)) {
                deletedOptions.push(optionId);
            }

            document.getElementById("deletedOptions").value = JSON.stringify(deletedOptions);
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

    </script>
@endsection
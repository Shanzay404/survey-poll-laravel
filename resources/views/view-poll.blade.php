@extends('layout.layout')

@section('content')

    <form class="w-full max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">View Poll</h2>

        <label class="block font-medium text-gray-700 mb-2">Survey Title</label>

        <input type="text"  value="{{ $poll->survey->title }}" class=" p-3 border border-gray-300 rounded  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold mb-4 focus:ring-2 focus:ring-indigo-500">
       


        <label class="block font-medium text-gray-700 mb-2">Poll Heading</label>

        <input type="text"  value="{{ $poll->heading }}" class=" p-3 border border-gray-300 rounded  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold mb-4 focus:ring-2 focus:ring-indigo-500">
       

        
        <label class="block font-medium text-gray-700 mb-2">Expiry Date</label>

        <input type="date"  value="{{ $poll->survey->expiry_date }}" class=" p-3 border border-gray-300 rounded  bg-gray-10 rounded shadow w-full text-gray-700 font-semibold mb-4 focus:ring-2 focus:ring-indigo-500">
       


        
        <label class="block font-medium text-gray-700 mb-2">Poll Options</label>

    
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

       


    </form>


<script>
    
// draggable functionality 
const optionContainer = document.getElementById('optionContainer');
const items = optionContainer.querySelectorAll('.option_item');


let draggedItem = null;

// document.querySelectorAll('.option_item').forEach(item => {
items.forEach(item => {
    item.addEventListener('dragstart', (e)=>{
        setTimeout(() => {
            draggedItem = item;
            item.classList.add("dragging");
        }, 0);
        console.log(item.id);
    });   
    item.addEventListener('dragend', ()=>{
        draggedItem = null;
        item.classList.remove('dragging');
        updateOptionOrder();

    });    
});

const initOptionContainer = (e) => {
    e.preventDefault();
    const draggingItem = optionContainer.querySelector('.dragging');
    const siblings = [...optionContainer.querySelectorAll(".option_item:not(.dragging)")];
    let nextSibling = siblings.find(sibling => {
        return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
    });
    // console.log(nextSibling);


    optionContainer.insertBefore(draggingItem,nextSibling);
   
}



optionContainer.addEventListener('dragover', initOptionContainer);
optionContainer.addEventListener('dragenter', e => e.preventDefault());


const updateOptionOrder = () => {
    let optionOrder = [];

    document.querySelectorAll('.option_item').forEach((item, index) => {
        let id = item.id.replace('option-', ''); // Extract numeric ID
        optionOrder.push({ id: id, position: index + 1 });
    });

    fetch('/update-option-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ order: optionOrder })
    })
    .then(response => response.json())
    .then(data => {
        console.log("IDs Updated Successfully", data);
    })
    .catch(error => console.error("Error:", error));
};




</script>
@endsection
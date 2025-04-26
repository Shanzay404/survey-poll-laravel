


<script>
    lucide.createIcons(); // Initialize Lucide Icons
    document.getElementById("typeSelect").addEventListener("change", function() {
        let pollSection = document.getElementById("pollOptions");
        let surveySection = document.getElementById("surveyQuestions");
        if (this.value === "poll") {
            pollSection.classList.remove("hidden");
            surveySection.classList.add("hidden");
        } else {
            surveySection.classList.remove("hidden");
            pollSection.classList.add("hidden");
        }
    });

    function addOption(event) {
    event.preventDefault();
    let container = document.getElementById("optionsList");
    let errorMessage = document.getElementById("errorMessage");

    if(container.children.length >= 10)
    {
        errorMessage.classList.remove('hidden');
    }
    else{
        errorMessage.classList.add('hidden');
        let div = document.createElement("div");
        div.classList.add("flex", "items-center", "gap-2","option-item","w-full");
    
        let input = document.createElement("input");
        input.type = "text";
        input.name = "optionText[]";
        input.placeholder = "New Option";
        input.classList.add("w-full", "p-2", "border", "option-item","shadow", "mb-2", "rounded", "focus:ring-2", "focus:ring-blue-500");
    
        div.appendChild(input);
        container.appendChild(div);
    }

}

   
    function addQuestion(event) {
        event.preventDefault();
        let container = document.getElementById("questionsList");
        let errorMessage = document.getElementById("SurveyErrorMessage");

        if(container.children.length >= 9 ){
            errorMessage.classList.remove('hidden');
        }else{
            errorMessage.classList.add('hidden');
            let div = document.createElement("div");
            div.classList.add("p-3", "bg-gray-100", "rounded");
            let input = document.createElement("input");
            input.type = "text";
            input.name = "surveyQuestion[]"; // Ensure it's an array
            input.placeholder = "New Question";
            input.classList.add("w-full", "p-2", "border", "rounded");
            let select = document.createElement("select");
            select.name = "questionType[]"; 
            select.classList.add("w-full", "p-2", "border", "rounded", "mt-2");
    
            let options = ["text", "single_select", "multi_select"];
            let optionLabels = ["Open-ended", "Single Select", "Multi Select"];
        
    
            options.forEach((val, index) => {
            let opt = document.createElement("option");
            opt.value = val;
            opt.textContent = optionLabels[index];
            select.appendChild(opt);
            });
    
            div.appendChild(input);
            div.appendChild(select);
            container.appendChild(div);

        }    

    }

document.addEventListener("DOMContentLoaded", function() {
let typeSelect = document.getElementById("typeSelect");
let pollSection = document.getElementById("pollOptions");
let surveySection = document.getElementById("surveyQuestions");
let survey_title = document.getElementById('survey_title');
let poll_heading_text = document.getElementById('poll_heading_text');

function toggleSections() {
    if (typeSelect.value === "poll") {
        pollSection.classList.remove("hidden");
        surveySection.classList.add("hidden");
        survey_title.classList.add("hidden");
        poll_heading_text.classList.add('hidden');
    } else if(typeSelect.value === "survey") {
        poll_heading_text.classList.remove('hidden');
        survey_title.classList.remove("hidden");
        pollSection.classList.remove("hidden");
        surveySection.classList.remove("hidden");

        // remove pollOptions 
        // let pollOptionsList = document.querySelector("[id='optionsList']");

        // if (pollOptionsField && pollOptionsList ) {
        //     console.log('poll options and list removed successfully');
        //     pollOptionsList.remove();
        //     pollOptionsField.remove();
        // }
    }
}

typeSelect.addEventListener("change", toggleSections);
toggleSections(); 
});



</script>
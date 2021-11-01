import './styles/multistep-survey.scss';

const multiStepContainer = document.querySelector('#multistep_survey');

// console.log(multiStepContainer);

if(multiStepContainer) {
    
    /**
     * Generate Steps elements
     */
    function generateSteps() {
        const allTabs = document.getElementsByClassName("tab");
        for (let i = 0; i < allTabs.length; i++) {
          
          let newStep = `<span class="step"></span>`;
        //   console.log(newStep)
          jQuery('.stepsConatiner').append(newStep);
        }
    }
    generateSteps();
    
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab
    // console.log(currentTab)
    function showTab(n) {
      // This function will display the specified tab of the form ...
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "block";
      // ... and fix the Previous/Next buttons:
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        // document.getElementById("nextBtn").innerHTML = "Submit";
        document.getElementById("nextBtn").remove();
        const submitButton = `<button type="submit" class="wadi_survey_submit">Submit</button>`;
        jQuery('.multistep_naviation').append(submitButton);
    
        // document.getElementById("nextBtn").classList.add("wadi_survey_submit");
      } else {
        document.getElementById("nextBtn").innerHTML = "Next";
      }
      // ... and run a function that displays the correct step indicator:
      fixStepIndicator(n)
    }
    
    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");
    //   console.log(x);
      // Exit the function if any field in the current tab is invalid:
      if (n == 1 && !validateForm()) return false;
      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form... :
    //   console.log(x.length)
      if (currentTab >= x.length) {
        //...the form gets submitted:
        // document.querySelector(".survey_container").submit();
        survey()
        return false;
      }
      // Otherwise, display the correct tab:
      showTab(currentTab);
    }
    
    function validateForm() {
      // This function deals with validation of the form fields
      var x, y, i, valid = true;
      x = document.getElementsByClassName("tab");
      y = x[currentTab].getElementsByTagName("input");
      // A loop that checks every input field in the current tab:
    //   for (i = 0; i < y.length; i++) {
        // If a field is empty...
        // if (y[i].value == "") {
        //   // add an "invalid" class to the field:
        //   y[i].className += " invalid";
        //   // and set the current valid status to false:
        //   valid = false;
        // }
    //   }
      // If the valid status is true, mark the step as finished and valid:
      if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
      }
      return valid; // return the valid status
    }
    
    function fixStepIndicator(n) {
      // This function removes the "active" class of all steps...
      var i, x = document.getElementsByClassName("step");
      for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
      }
      //... and adds the "active" class to the current step:
      x[n].className += " active";
    }
    
      const prevButton = document.querySelector('#prevBtn');
      const nextButton = document.querySelector('#nextBtn');
    
    //   console.log(nextButton)
    
      prevButton.addEventListener('click', function(e){
        // console.log(e)
        // console.log(currentTab)
    
        nextPrev(-1);
        gatherMultiQuestion(e);
      })
      nextButton.addEventListener('click', function(e){
        // console.log(e)
        // console.log(currentTab)
    
        nextPrev(1);
        gatherMultiQuestion(e)
      })
    
      function gatherMultiQuestion(e) {
        document.querySelectorAll(".multiple_question_container").forEach((container) => {
            // console.log(container);
            let containerId = container.getAttribute("id");
            // console.log(containerId);
            var answers = [];
    
            console.log(containerId);
    
            jQuery('#' + containerId).find('input:checked').each(function() {
              answers.push(jQuery(this).attr('data-answer'));
            //   console.log("The ANSWERS: ",answers)
            });
            console.log(answers);
          answers = answers.filter(function( element ) {
            return element !== undefined;
         });
    
          jQuery('#' + containerId).find('input[type=hidden]').val(answers);
          });
      }
    
    
    
    
      function survey() {
        const surveyContainer = document.querySelector(".survey_container");
        const surveySubmit = document.querySelector(".wadi_survey_submit");
        if (surveyContainer !== null) {
        const gatherData = () => {
          jQuery(".survey_container").submit(function (e) {
            e.preventDefault();
            document.querySelectorAll(".multiple_question_container").forEach((container) => {
              let containerId = container.getAttribute("id");
              var answers = [];
    
              console.log(containerId);
      
              jQuery('#' + containerId).find('input:checked').each(function() {
                answers.push(jQuery(this).attr('data-answer'));
              });
              console.log(answers);
            answers = answers.filter(function( element ) {
              return element !== undefined;
           });
      
            jQuery('#' + containerId).find('input[type=hidden]').val(answers);
            });
            console.log("Gathering Data");
            let form_data = jQuery('.survey_container').serializeArray();
      
            console.log(form_data);
            const theData = JSON.stringify(form_data);
            let dataCollection = {
              user_id: surveyContainer.dataset.userId,
              post_type: surveyContainer.dataset.postType,
              survey_id: surveyContainer.dataset.surveyId,
              surveyData: theData,
            };
      
            const data = {
              action: "getQuizData",
              data: { ...dataCollection },
            };
            jQuery.ajax({
              url: ajaxurl,
              type: "POST",
              data: data,
              datatype: "json",
              success: (response) => {
                console.log("THE RESPONSE: ", response);
              },
              error: (err) => {
                console.log(err);
              },
            });
      
      
          });
        };
        gatherData();
      }
      
      };
      
      /**
       * Check if Survey Container Exists then run the script
       */
    //   const surveyConatiner = document.querySelector(".survey_container");
      
    //   if (surveyConatiner) {
    //     survey();
    //   }
      

}
import './styles/multistep-survey.scss';

const multiStepContainer = document.querySelector('#multistep_survey');

if(multiStepContainer) {
    
    /**
     * Generate Steps elements
     */
    function generateSteps() {
        const allTabs = document.getElementsByClassName("tab");
        for (let i = 0; i < allTabs.length; i++) {
          
          let newStep = `<span class="step"></span>`;
          jQuery('.stepsConatiner').append(newStep);
        }
    }
    generateSteps();
    
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab
    function showTab(n) {
      // This function will display the specified tab of the form ...
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "block";
      // ... and fix the Previous/Next buttons:
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else{
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        const nextButton = jQuery("#nextBtn");
        nextButton.html('Submit');
        setTimeout(() => {
          nextButton.removeAttr("type").attr("type", "submit");
        }, 250);
        nextButton.addClass("wadi_survey_submit");
      } else {
        const nextButton = jQuery("#nextBtn");
        nextButton.removeAttr("type").attr("type", "button");
        document.getElementById("nextBtn").innerHTML = "Next";
      }

      // ... and run a function that displays the correct step indicator:
      fixStepIndicator(n)
    }
    
    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");
      // Exit the function if any field in the current tab is invalid:
      if (n == 1 && !validateForm()) return false;
      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form... :
      if (currentTab >= x.length) {
        //...the form gets submitted:
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
        if(document.querySelector(".active")) {
        document.querySelector(".step").classList.contains('.active') && document.querySelector(".step").classList.remove("finish");
        }
        document.getElementsByClassName("step")[currentTab].classList.add("finish");
      }
      return valid; // return the valid status
    }
    
    function fixStepIndicator(n) {
      // This function removes the "active" class of all steps...
      var i, x = document.getElementsByClassName("step");
      for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
      }
      x[n].classList.add("active");
    }
    
      const prevButton = document.querySelector('#prevBtn');
      const nextButton = document.querySelector('#nextBtn');
    
      prevButton.addEventListener('click', function(e){
        nextPrev(-1);
        gatherMultiQuestion(e);
      })
      nextButton.addEventListener('click', function(e){
        nextPrev(1);
        gatherMultiQuestion(e)
      })
    
      function gatherMultiQuestion(e) {
        document.querySelectorAll(".multiple_question_container").forEach((container) => {
            let containerId = container.getAttribute("id");
            var answers = [];
    
            jQuery('#' + containerId).find('input:checked').each(function() {
              answers.push(jQuery(this).attr('data-answer'));
            });
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
                document.querySelector('#prevBtn').style.display = 'none';
                document.querySelector('.wadi_survey_submit').style.display = 'none';
                document.querySelector('.stepsConatiner').style.display = 'none';
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
      const surveyConatiner = document.querySelector(".survey_container");
      
      if (surveyConatiner) {
        survey();
      }
      

}
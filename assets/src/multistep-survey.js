import './styles/multistep-survey.scss';

const multiStepContainer = document.querySelector('#multistep_survey');
const redirectDiv = document.querySelector(".redirect_url");
const redirectUrl = redirectDiv.dataset.redirectUrl;
const redirectSetTimeout = redirectDiv.dataset.redirectTime;
if(multiStepContainer) {

    
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
        nextButton.addClass("wadi_survey_multisteps_submit");
      } else {
        const nextButton = jQuery("#nextBtn");
        nextButton.removeAttr("type").attr("type", "button");
        document.getElementById("nextBtn").innerHTML = "Next";
      }
    }
    
    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");

      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form... :
      if (currentTab >= x.length) {
        //...the form gets submitted:
        return false;
      }
      // Otherwise, display the correct tab:
      showTab(currentTab);
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
    
    
    
    
      function surveyMultipleSteps() {
        const surveymultiStepContainer = document.querySelector(".survey_multistep_container");
        const surveySubmit = document.querySelector(".wadi_survey_multisteps_submit");
        if (surveymultiStepContainer !== null) {
        const gatherData = () => {
          jQuery(".survey_multistep_container").submit(function (e) {
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
            let form_data = jQuery('.survey_multistep_container').serializeArray();
      
            console.log(form_data);
            const theData = JSON.stringify(form_data);
            let dataCollection = {
              user_id: surveymultiStepContainer.dataset.userId,
              post_type: surveymultiStepContainer.dataset.postType,
              survey_id: surveymultiStepContainer.dataset.surveyId,
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
                document.querySelector('.wadi_survey_multisteps_submit').style.display = 'none';
                console.log("THE RESPONSE: ", response);
                if(redirectUrl) {
                  setTimeout(()=> {
                    window.location.href = `${redirectUrl}`;
                  }, redirectSetTimeout)
                }
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
      const surveyMultiSteps = document.querySelector("#multistep_survey");
      
      if (surveyMultiSteps) {
        surveyMultipleSteps();
      }
      

}
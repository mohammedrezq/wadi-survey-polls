const survey = () => {
  const surveyContainer = document.querySelector(".survey_container");
  const surveySubmit = document.querySelector(".wadi_survey_submit");
  if (surveyContainer !== null) {
  const gatherData = () => {
    jQuery(".wadi_survey_submit").click(function () {
      document.querySelectorAll(".multiple_container").forEach((container) => {
        let containerId = container.getAttribute("id");
        var answers = [];

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
const surveyConatiner = document.querySelector(".survey_container");

if (surveyConatiner) {
  survey();
}

import './styles/single-survey.scss';

import './range-scale';
const survey = () => {
  const surveyContainer = document.querySelector(".survey_container");
  const surveySubmit = document.querySelector(".wadi_survey_submit");
  const redirectDiv = document.querySelector(".redirect_url");
  const redirectUrl = redirectDiv.dataset.redirectUrl;
  const redirectSetTimeout = redirectDiv.dataset.redirectTime;
  const surveyFinishMessage = redirectDiv.dataset.surveyFinishMessage;
  console.log(redirectSetTimeout);
  // const surveyAlreadyTaken = redirectDiv.dataset.surveyAlreadyTakenMessage;

  if (surveyContainer !== null) {
    const gatherData = () => {
      jQuery(".survey_container").submit(function (e) {
        e.preventDefault();
        document
          .querySelectorAll(".multiple_container")
          .forEach((container) => {
            let containerId = container.getAttribute("id");
            var answers = [];

            jQuery("#" + containerId)
              .find("input:checked")
              .each(function () {
                answers.push(jQuery(this).attr("data-answer"));
              });
            console.log(answers);
            answers = answers.filter(function (element) {
              return element !== undefined;
            });

            jQuery("#" + containerId)
              .find("input[type=hidden]")
              .val(answers);
          });
        console.log("Gathering Data");
        let form_data = jQuery(".survey_container").serializeArray();

        console.log(form_data);
        const theData = JSON.stringify(form_data);
        let dataCollection = {
          user_id: surveyContainer.dataset.userId,
          post_type: surveyContainer.dataset.postType,
          survey_id: surveyContainer.dataset.surveyId,
          surveyData: theData,
        };

        const data = {
          action: "getSurveyData",
          data: { ...dataCollection },
        };
        jQuery.ajax({
          url: ajaxurl,
          type: "POST",
          data: data,
          datatype: "json",
          success: (response) => {
            console.log("THE RESPONSE: ", response);
            // surveyConatiner.innerHTML = "";
            if(redirectDiv && surveyFinishMessage) {
              jQuery('.survey_container').empty();
              jQuery('.survey_container').append(surveyFinishMessage);
            }
            // surveyConatiner.innerHTML = surveyFinishMessage;
            console.log(redirectSetTimeout);
            if (redirectUrl) {
              setTimeout(() => {
                window.location.href = `${redirectUrl}`;
              }, redirectSetTimeout);
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
const surveyConatiner = document.querySelector(".survey_container");

if (surveyConatiner) {
  survey();
}

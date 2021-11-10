import './styles/single-poll.scss';

import './poll-range-scale';

console.log("TEST PUSH FROM POLL");

const pollAjax = () => {
  const pollContainer = document.querySelector(".poll_container");
//   const surveySubmit = document.querySelector(".wadi_survey_submit");
//   const redirectDiv = document.querySelector(".redirect_url");
//   const redirectUrl = redirectDiv.dataset.redirectUrl;
//   const redirectSetTimeout = redirectDiv.dataset.redirectTime;
//   const surveyFinishMessage = redirectDiv.dataset.surveyFinishMessage;
  // const surveyAlreadyTaken = redirectDiv.dataset.surveyAlreadyTakenMessage;

  if (pollContainer !== null) {
    const gatherPollData = () => {
      jQuery(".poll_container").submit(function (e) {
        e.preventDefault();
        document
          .querySelectorAll(".poll_multiple_container")
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
        console.log("Gathering Poll Data");
        let form_data = jQuery(".poll_container").serializeArray();

        console.log("Poll",form_data);
        const theData = JSON.stringify(form_data);
        let dataCollection = {
          user_id: pollContainer.dataset.userId,
          post_type: pollContainer.dataset.postType,
          poll_id: pollContainer.dataset.pollId,
          pollData: theData,
        };

        const data = {
          action: "getPollData",
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


            // if(redirectDiv && surveyFinishMessage) {
            //   jQuery('.survey_container').empty();
            //   jQuery('.survey_container').append(surveyFinishMessage);
            // }
            // surveyConatiner.innerHTML = surveyFinishMessage;
            // if (redirectUrl) {
            //   setTimeout(() => {
            //     window.location.href = `${redirectUrl}`;
            //   }, redirectSetTimeout);
            // }


          },
          error: (err) => {
            console.log(err);
          },
        });
      });
    };
    gatherPollData();
  }
};

/**
 * Check if Survey Container Exists then run the script
 */
const pollContainer = document.querySelector(".poll_container");

if (pollContainer) {
    pollAjax();
}

import './styles/single-poll.scss';

import './poll-range-scale';

const pollAjax = () => {
  const pollContainer = document.querySelector(".poll_container");
//   const surveySubmit = document.querySelector(".wadi_survey_submit");
  const pollHiddenInputDiv = document.querySelector(".poll_redirect_url");
  console.log(pollHiddenInputDiv);
  const pollRedirectUrl = pollHiddenInputDiv.dataset.pollRedirectUrl;
  const pollRedirectSetTimeout = pollHiddenInputDiv.dataset.pollRedirectTime;
  const pollFinishMessage = pollHiddenInputDiv.dataset.pollFinishMessage;
  const pollAlreadyTaken = pollHiddenInputDiv.dataset.pollAlreadyTakenMessage;

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


            if(pollHiddenInputDiv && pollFinishMessage) {
              jQuery('.survey_container').empty();
              jQuery('.survey_container').append(pollFinishMessage);
            }
            pollContainer.innerHTML = pollFinishMessage;
            if (pollRedirectUrl) {
              setTimeout(() => {
                window.location.href = `${pollRedirectUrl}`;
              }, pollRedirectSetTimeout);
            }


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

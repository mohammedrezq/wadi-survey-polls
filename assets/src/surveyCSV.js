console.log("EXPORT STUDFFFFF");
const exportSurveryCSV = () => {
  const surveySingleSubmissionsPage = document.querySelector(
    ".admin_page_single_survey"
  );
  if (surveySingleSubmissionsPage) {
    console.log("Hello Single Submissions PAGE MOOD..");
    document
      .querySelector("#export_btn")
      .addEventListener("click", function () {
        console.log("Button clicked");

        const paramId = document.querySelector("#export_btn");
        const theId = paramId.dataset.survey;
        const data =  {
          action: "export_survey_results_to_csv",
          paramId: theId,
        };
        console.log("EXPORT STUDFFFFF BUTTONNNNN");
        console.log(ajaxurl);
        console.log(data);
        jQuery.ajax({
          url: ajaxurl,
          type: "POST",
          data: data,
          datatype: "json",
          success: (response) => {
            console.log(response)
            var downloadLink = document.createElement("a");
            var fileData = ["\ufeff" + response];

            var blobObject = new Blob(fileData, {
              type: "text/csv;charset=utf-8;",
            });
            // Get date
            var url = URL.createObjectURL(blobObject);
            downloadLink.href = url;
            var today = new Date();
            var date =
              today.getFullYear() +
              "-" +
              (today.getMonth() + 1) +
              "-" +
              today.getDate();

            // Do the magic
            downloadLink.download = "quizzes_" + date + ".csv";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
          },
          error: (error) => {
            console.log(error + "Error Warning");
          },
        });
      });
  }
  console.log("Button again");
};
exportSurveryCSV();

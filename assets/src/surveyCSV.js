const exportSurveryCSV = () => {
  const surveySingleSubmissionsPage = document.querySelector(
    ".admin_page_single_survey"
  );
  if (surveySingleSubmissionsPage) {
    document
      .querySelector("#export_btn")
      .addEventListener("click", function () {
        const paramId = document.querySelector("#export_btn");
        const theId = paramId.dataset.survey;
        const data = {
          action: "wadi_survey_export_survey_results_to_csv",
          paramId: theId,
        };
        jQuery.ajax({
          url: ajaxurl,
          type: "POST",
          data: data,
          datatype: "json",
          success: (response) => {
            console.log(response);
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
              today.getDate()
              +
              "-" +
              today.getHours() +
              "-" +
              today.getMinutes();

            // Do the magic
            downloadLink.download = "wadi-survey_" + date + ".csv";
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
};
exportSurveryCSV();

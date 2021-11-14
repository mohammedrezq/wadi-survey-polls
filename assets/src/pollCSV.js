const exportSurveryCSV = () => {
    const pollSingleSubmissionsPage = document.querySelector(
      ".admin_page_single_poll"
    );
    if (pollSingleSubmissionsPage) {
      document
        .querySelector("#export_btn")
        .addEventListener("click", function () {
          const paramPollId = document.querySelector("#export_btn");
          const theId = paramPollId.dataset.poll;
          const data = {
            action: "export_poll_results_to_csv",
            paramPollId: theId,
          };
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: data,
            datatype: "json",
            success: (response) => {
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
              downloadLink.download = "wadi-poll_" + date + ".csv";
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
  
import './styles/tooltip.scss';

console.log("Tooltip JS");

const downloadCSVBtn = document.querySelector("#export_btn");

document.addEventListener("click", function (e) {
  if (e.target.id == 'export_btn') {
      if (e.target.classList.contains('active')) {
        e.target.classList.remove('active');
      } else {
        e.target.classList.add('active');
      }
  } else if (e.target.classList.contains('tooltip_text')) {
    downloadCSVBtn.classList.add('active');
  } else {
    downloadCSVBtn.classList.remove('active');
  }
});

const upload_resume = document.querySelector("#upload_resume");
const showFileInfo = document.querySelector('.show-file-info');

upload_resume.addEventListener("change", function(event) {
  let file = event.target.files[0];

  showFileInfo.textContent = "";

  if(!file) {
    showFileInfo.textContent = "No file selected";
    return;
  }

  // Validate file type 
  const fileType = file.type === "application/pdf";

  if(!fileType) {
    showFileInfo.textContent = "Please upload a PDF file";
    showFileInfo.style.color = "red";
    return;
  }

  const maxSize = 3 * 1024 * 1024;

  if(file.size > maxSize) {
    showFileInfo.textContent = "File size exceeds 3MB";
    showFileInfo.style.color = "red";
    return;
  }

  showFileInfo.style.color = "green";
  showFileInfo.textContent = file.name;
});
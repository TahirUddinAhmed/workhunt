document.addEventListener("DOMContentLoaded", function() {
  // Function to handle file upload validation
  function handleFileUpload(event, allowedTypes, maxSize, showFileInfoElement) {
    let file = event.target.files[0];

    showFileInfoElement.textContent = "";

    if (!file) {
      showFileInfoElement.textContent = "No file selected";
      return;
    }

    // Validate file type
    const fileType = allowedTypes.includes(file.type);

    if (!fileType) {
      showFileInfoElement.textContent = `Please upload a file of type: ${allowedTypes.join(', ')}`;
      showFileInfoElement.style.color = "red";
      return;
    }

    if (file.size > maxSize) {
      showFileInfoElement.textContent = `File size exceeds ${maxSize / (1024 * 1024)}MB`;
      showFileInfoElement.style.color = "red";
      return;
    }

    showFileInfoElement.textContent = `Selected file: ${file.name}`;
    showFileInfoElement.style.color = "green";
  }

  // Event listener for resume upload
  const upload_resume = document.querySelector("#upload_resume");
  const showFileInfo = document.querySelector('.show-file-info');

  if (upload_resume && showFileInfo) {
    upload_resume.addEventListener("change", function(event) {
      handleFileUpload(event, ["application/pdf"], 3 * 1024 * 1024, showFileInfo);
    });
  }

  // Event listener for company logo upload
  const company_logo = document.querySelector('#company_logo');
  const showLogoInfo = document.querySelector('.show-logo-info');

  if (company_logo && showLogoInfo) {
    company_logo.addEventListener("change", function(event) {
      handleFileUpload(event, ['image/png', 'image/jpg', 'image/jpeg', 'image/webp'], 2 * 1024 * 1024, showLogoInfo);
    });
  }
});
$(document).ready(function () {
  var phonePattern = /^\d{10,15}$/; // Adjust as needed

  // Event handler for changes in date of birth inputs
  $(".bdate").on("change", function () {
    var birthDate = new Date($(this).val());
    // Find the corresponding age input within the same row
    var ageInput = $(this).closest(".row").find(".age");
    var age = calculateAge(birthDate);
    ageInput.val(age);
  });

  function calculateAge(birthDate) {
    var today = new Date();
    var age = today.getFullYear() - birthDate.getFullYear();
    var monthDifference = today.getMonth() - birthDate.getMonth();
    var dayDifference = today.getDate() - birthDate.getDate();

    if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
      age--;
    }

    return age;
  }

  function validateEmails(form) {
    var isValid = true;

    $(form)
      .find(".email-group")
      .each(function () {
        var email = $(this).find(".email").val();
        var confirmEmail = $(this).find(".confirm-email").val();

        if (email !== confirmEmail) {
          isValid = false;
          $(this).find(".confirm-email").addClass("is-invalid");
          $(this)
            .find(".invalid-feedback")
            .text("Email addresses do not match.")
            .show(); // Show invalid feedback
        } else {
          $(this)
            .find(".confirm-email")
            .removeClass("is-invalid")
            .addClass("is-valid");
          $(this).find(".invalid-feedback").hide(); // Hide invalid feedback
        }
      });

    return isValid;
  }

  function validatePhones(form) {
    var isValid = true;
    $(form)
      .find(".number")
      .each(function () {
        var phone = $(this).find(".contact-number").val();
        if (!phonePattern.test(phone)) {
          isValid = false;
          $(this).addClass("is-invalid");
          $(this).siblings(".phone.invalid-feedback").show(); // Show invalid feedback
        } else {
          $(this).removeClass("is-invalid").addClass("is-valid");
          $(this).siblings(".phone.invalid-feedback").hide(); // Hide invalid feedback
        }
      });
    return isValid;
  }

  function validateImages(form) {
    var isValid = true;

    // Clear previous feedback messages
    $(form).find(".img.invalid-feedback").hide();

    // Iterate over each file input
    $(form)
      .find(".form-control-file.valid")
      .each(function () {
        var fileInput = $(this)[0];
        var file = fileInput.files[0];
        var feedback = $(this).siblings(".img.invalid-feedback");

        // Check if a file was selected
        if (!file) {
          feedback.text("Please upload a file").show();
          isValid = false;
          return;
        }

        var maxSize = 5 * 1024 * 1024; // 5MB in bytes
        var allowedTypes = ["image/jpeg", "image/png"];

        // Validate file size
        if (file.size > maxSize) {
          feedback
            .text("Sorry, your file is too large. Maximum size is 5MB.")
            .show();
          isValid = false;
          return;
        }

        // Validate file type
        if (!allowedTypes.includes(file.type)) {
          feedback.text("Sorry, only JPG & PNG files are allowed.").show();
          isValid = false;
          return;
        }

        // If file is valid, hide feedback
        feedback.hide();
      });

    return isValid;
  }

  // Handle form submission
  window.addEventListener(
    "load",
    function () {
      var forms = document.getElementsByClassName("needs-validation");

      Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            var isCustomValid =
              validateEmails(form) &&
              validatePhones(form) &&
              validateImages(form);
            // Check Bootstrap validation and custom validations
            if (form.checkValidity() === false || !isCustomValid) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false
        );
      });
    },
    false
  );

  function handleFormSubmission(buttonId, ajaxUrl) {
    $(buttonId).click(function (e) {
      var form = $(this).closest("form")[0];

      var isCustomValid =
        validateEmails(form) && validatePhones(form) && validateImages(form);

      if (form.checkValidity() === false || !isCustomValid) {
        form.classList.add("was-validated");
        return;
      }

      console.log(form);

      Swal.fire({
        title: "Are you sure?",
        text: "Please review your information before moving on to the next page.",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, save and proceed",
      }).then((result) => {
        if (result.isConfirmed) {
          e.preventDefault(); // Prevent default form submission

          if (ajaxUrl) {
            $(".overlay").show();
            // If AJAX URL is provided, perform AJAX request
            var formData = $("#team-building-form").serialize();
            formData += "&educational=1"; // Add the educational key

            $.ajax({
              url: ajaxUrl,
              type: "POST",
              data: formData,
              success: function (response) {
                $("#ticketed").hide();
                $("#personal-info").show();
                $(".row.info").html(response); // Update content
              },
              error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.error("Response Text:", xhr.responseText); // Debugging line
              },
              complete: function () {
                $(".overlay").hide();
              },
            });
          } else {
            // If no AJAX URL is provided, trigger form submission
            $(form).find('button[type="submit"]').trigger("click");
          }
        }
      });
    });
  }

  handleFormSubmission(".nextSubmit");
  handleFormSubmission("#next"); // For the button with id 'next'
  handleFormSubmission("#next-t", "inc/team-building.php");

  $("a").click(function (e) {
    // Remove 'active' class from all buttons
    $("a").removeClass("active");

    // Add 'active' class to the clicked button
    $(this).addClass("active");

    // Get the value from the data attribute and set it to the hidden input
    var value = $(this).data("value");
    $("#tickettype").val(value);

    // Show or hide the arrival input based on the clicked button
    if (value === "One Way") {
      $(".arrival-container").hide(); // Hide if 'One Way' is clicked
      $("#arrival").removeAttr("required"); // Remove 'required' attribute
    } else {
      $(".arrival-container").show(); // Show if other buttons are clicked
      $("#arrival").attr("required", "required"); // Add 'required' attribute
    }

    if (value === "Multi-City") {
      $("#add-flight").show();
      $("#remove-flight").show();
    } else {
      $("#add-flight").hide();
      $("#remove-flight").hide();
    }
  });

  $("#add-flight").click(function () {
    const flightFormHTML = `
        <div class="row w-100 append-flight">
          <div class="row mt-4 wd-100">
              <div class="col-4">
                  <label for="origin">Origin</label><label for="" class="required">*</label>
                  <input class="form-control" list="datalistOptions" name="aorigin[]" placeholder="Type to search origin" required>
                  <datalist class="origin">
                  </datalist>
              </div>
              <div class="col-4">
                  <label for="destination">Destination</label><label for="" class="required">*</label>
                  <input class="form-control" list="datalistOptions" name="adestination[]" placeholder="Type to search destination" required>
                  <datalist class="origin">
                  </datalist>
              </div>

          </div>

          <div class="row mt-4 wd-100">
              <div class="col-4">
                  <label for="departure">Departure</label><label for="" class="required">*</label>
                  <input type="date" placeholder="Departure" name="adeparture[]"
                      class="form-control" required>
              </div>

              <div class="col-4">
                  <label for="classtype">Class Type</label><label for="" class="required">*</label>
                  <select name="aclasstype[]" class="form-control">
                      <option selected>Economy</option>
                      <option>Premium Economy</option>
                      <option>Business</option>
                      <option>First Class</option>
                  </select>
              </div>
          </div>
      </div>
    `;

    // Append the new form directly to the container
    $(".last").after(flightFormHTML);
  });

  $("#remove-flight").click(function () {
    $(".append-flight").last().remove();
  });

  // Initialize visibility based on the current ticket type value
  var initialValue = $("#tickettype").val();
  if (initialValue === "One Way") {
    $(".arrival-container").hide();
    $("#arrival").removeAttr("required"); // Remove 'required' attribute
  } else {
    $(".arrival-container").show();
    $("#arrival").attr("required", "required"); // Add 'required' attribute
  }
});

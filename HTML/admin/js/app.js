$(document).ready(function () {
  var urlCounts = "ajax/fetch-counts.php";
  var urlA = "ajax/pending.php";

  $(document).ajaxSend(function () {
    $(".overlay").fadeIn(300);
  });

  $("#branch").change(function () {
    branch = $(this).children(":selected").attr("value");
    adminid = $(this).children(":selected").attr("class");

    // First AJAX request for quotations
    $.ajax({
      type: "POST",
      url: urlA,
      data: {
        branch: branch,
        adminid: adminid,
      },
      success: function (data) {
        $(".row.frame-quote").html(data); // Update the HTML for quotations
      },
      error: function (e) {
        alert("Error in fetching quotation data");
      },
    }).done(function () {
      // Third AJAX request for counts
      $.ajax({
        type: "POST",
        url: urlCounts,
        data: {
          branch: branch,
        },
        dataType: "json",
        success: function (response) {
          // Update the counts in the HTML
          $("#pending_count").text(response.pending_count);
          $("#ticket_count").text(response.ticket_count);
          $("#customize_count").text(response.customize_count);
          $("#educ_count").text(response.educ_count);
          $("#tour_count").text(response.tour_count);
        },
        error: function () {
          alert("Error fetching counts");
        },
      }).done(function () {
        $(".overlay").fadeOut(300);
      });
    });
  });

  console.log(branch);

  var totalPrice = 0;

  function calculateTotal() {
    // Get the value from the label and convert to float
    var totalPax = parseFloat($("#total_pax").text()) || 0; // Directly get the label text
    var pricePax = parseFloat($("#price_pax").val()) || 0; // Get price per pax from input
    totalPrice = totalPax * pricePax; // Compute total price

    $("#total_amount").val(totalPrice);
    $("#total_price").text(
      totalPrice.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    ); // Update total price in the HTML
  }

  // Attach keyup event to the price input field
  $("#price_pax").on("keyup", function () {
    calculateTotal();
    calculateRemainingBalance();
  });

  $("#downpayment").on("keyup", calculateRemainingBalance);

  function calculateRemainingBalance() {
    var downpayment = parseFloat($("#downpayment").val()) || 0;
    var remainingBalance = totalPrice - downpayment;

    $("#remaining_bal").val(remainingBalance);

    $("#remaining_balance").text(
      remainingBalance.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    );

    // Calculate payment details based on installment type
    var installmentType = window.installmentType; // Fetch from PHP
    calculatePaymentDetails(remainingBalance, installmentType);
  }

  function calculatePaymentDetails(remainingBalance, installmentType) {
    var travelDateStr = $("#travel_date").text(); // e.g., "July 21, 2024"

    // Check if installmentType is null or empty
    if (!installmentType) {
      $("#installments").children("p").not("#remaining_balance").remove(); // Clear previous installments
      $("#installments").append("<p>Payment will be made in full.</p>"); // Show full payment message
      return; // Exit the function
    }

    // Convert to Date object
    var travelDate = new Date(travelDateStr);
    var today = new Date(); // Current date

    // Ensure the travel date is after today for valid calculation
    if (travelDate < today) {
      alert("Travel date must be in the future.");
      return;
    }

    // Initialize an array to hold installment dates
    var installmentDates = [];
    var currentDate = new Date(today); // Start with the current date
    var incrementMonths = installmentType === "Bimonthly" ? 2 : 4; // Set increment based on installment type

    // Calculate installment periods
    currentDate.setMonth(currentDate.getMonth() + incrementMonths); // Move to the first installment date
    while (currentDate <= travelDate) {
      // End date
      // Format the date to match "July 21, 2024"
      var formattedDate = currentDate.toLocaleString("default", {
        month: "long",
        day: "numeric",
        year: "numeric",
      });
      installmentDates.push(formattedDate); // Add formatted date to array
      currentDate.setMonth(currentDate.getMonth() + incrementMonths); // Move to the next installment date
    }

    // Clear previously appended installments
    $("#installments").children("p").not("#remaining_balance").remove();

    // Calculate the number of installments
    var numInstallments = installmentDates.length;

    $("#installment_number").val(numInstallments);
    // Calculate the amount per installment
    var amountPerInstallment =
      numInstallments > 0 ? remainingBalance / numInstallments : 0;

    var firstInstallmentDate =
      installmentDates.length > 0 ? installmentDates[0] : "";
    // Set the hidden input values // This should be the date

    $("#price_to_pay").val(amountPerInstallment);
    // Append each installment date with the installment amount to the <td> element
    let dueDates = [];

    installmentDates.forEach(function (date) {
      $("#installments").append(
        "<p>" +
          date +
          ": <span class=w-700>PHP " +
          amountPerInstallment.toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }) +
          "</span></p>"
      );
      dueDates.push(date);
    });

    $("#due_date").val(dueDates.join(". "));
  }

  // Initial calculations
  calculateTotal();
  calculateRemainingBalance();
});

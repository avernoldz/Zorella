$(document).ready(function () {
  var config = {
    cUrlCountires: "https://api.countrystatecity.in/v1/countries",
    cUrlProv: "https://psgc.gitlab.io/api/provinces/",
    cUrlBrgy: "https://psgc.gitlab.io/api/municipalities/",
    ckey: "NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==",
  };

  var $countrySelect = $(".origin"),
    $flightSelect = $(".flight");

  function loadCountries() {
    var apiEndPoint = config.cUrlCountires;
    var apiDomestic = config.cUrlProv,
      $spinnerContainer = $(".overlay");

    $countrySelect.empty().append('<option value=""></option>');

    var val = $flightSelect.val();

    $spinnerContainer.show();

    if (val == "Domestic") {
      $.ajax({
        url: apiDomestic,
        method: "GET",
        dataType: "json",
        success: function (data) {
          $.each(data, function (index, province) {
            $countrySelect.append(
              $("<option>", {
                id: province.code,
                value: province.name,
                text: province.name,
              })
            );
          });
          $spinnerContainer.hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error loading countries:", textStatus, errorThrown);
          $spinnerContainer.hide();
        },
      });
    } else {
      $.ajax({
        url: apiEndPoint,
        method: "GET",
        headers: {
          "X-CSCAPI-KEY": config.ckey,
        },
        dataType: "json",
        success: function (data) {
          $.each(data, function (index, country) {
            $countrySelect.append(
              $("<option>", {
                id: country.iso2,
                value: country.name,
                text: country.name,
              })
            );
          });
          $spinnerContainer.hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error loading countries:", textStatus, errorThrown);
          $spinnerContainer.hide();
        },
      });
    }
  }

  loadCountries(); // Call the function when the document is ready

  $("#flighttype").change(function () {
    loadCountries();
  });
});

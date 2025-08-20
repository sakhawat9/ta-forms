(function ($) {
  "use strict";
  async function userInformation() {
    const url = "https://ipwhois.app/json/";
    try {
      const response = await fetch(url);
      const result = await response.json();
      localStorage.setItem("ta_form_user_information", JSON.stringify(result));
    } catch (error) {
      console.error(error.message);
    }
  }
  userInformation();

  // Wrap entire code inside foreach for each .ta_forms class element
  $(".ta_forms").each(function () {
    var $ta_forms = $(this);
    const shortcodeId = $ta_forms.data("shortcode_id");

    if ($(".form").length) {
      $(".form").validate();
    }

    $ta_forms.find(".form").on("submit", function (e) {
      e.preventDefault();

      const $form = $(this);
      if (!$form.valid()) {
        return;
      }
      const formData = $(this).serialize();
      const formId = $form.data("form_id");

      function getBrowserName() {
        const userAgent = navigator.userAgent;

        if (userAgent.indexOf("Firefox") > -1) {
          return "Firefox";
        } else if (userAgent.indexOf("SamsungBrowser") > -1) {
          return "Samsung Internet";
        } else if (
          userAgent.indexOf("Opera") > -1 ||
          userAgent.indexOf("OPR") > -1
        ) {
          return "Opera";
        } else if (userAgent.indexOf("Edg") > -1) {
          return "Edge";
        } else if (userAgent.indexOf("Chrome") > -1) {
          return "Chrome";
        } else if (userAgent.indexOf("Safari") > -1) {
          return "Safari";
        } else {
          return "Unknown";
        }
      }

      let userData = {
        device: /Mobi|Android/i.test(navigator.userAgent)
          ? "Mobile"
          : "Desktop",
        browser: getBrowserName(), // function from earlier
        platform: navigator.platform,
        screen: screen.width + "x" + screen.height,
        language: navigator.language,
        vendor: navigator.vendor,
        url: window.location.href,
        referrer: document.referrer,
      };

      const taFormUserInfo = localStorage.getItem("ta_form_user_information");
      const ipInfo = taFormUserInfo ? JSON.parse(taFormUserInfo) : {};
      const userInfo = { ...userData, ...ipInfo };

      // Now userData is defined here
      $.ajax({
        url: frontend_scripts.ajax_url,
        type: "post",
        data: {
          action: "ta_forms_send_email",
          data: formData,
          form_id: formId,
          nonce: frontend_scripts.nonce,
          userInfo,
        },
        success: function (response) {
          if (response.data.type === "success") {
            if (response.data.redirect) {
              window.location.href = response.data.redirect;
              $form.trigger("reset");
            } else {
              Swal.fire({
                icon: "success",
                title: response.data.title,
                text: response.data.description,
                timer: 2000,
                showConfirmButton: false,
              }).then(function () {
                $form.trigger("reset");
              });
            }
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.title,
              text: `${response.data.title}\n${response.data.description}`,
              confirmButtonText: `${response.data.okay}`,
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: errorThrown,
            confirmButtonText: "OK",
          });
        },
      });
    });
  });
})(jQuery);

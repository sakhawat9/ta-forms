(function ($) {
  "use strict";

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

      let userData = {
  device: /Mobi|Android/i.test(navigator.userAgent) ? "Mobile" : "Desktop",
  browser: navigator.userAgent,                 // full browser string
  platform: navigator.platform,                 // MacIntel, Win32, etc.
  os: navigator.appVersion,                     // OS info (rough)
  screen: screen.width + "x" + screen.height,   // screen size
  language: navigator.language,                 // en-GB, en-US, etc.
  vendor: navigator.vendor,                     // Google Inc., Apple, etc.
  url: window.location.href,                    // current page
  referrer: document.referrer,                  // referrer
  timezone: Intl.DateTimeFormat().resolvedOptions().timeZone, // e.g. Asia/Dhaka
};
console.log('userData', userData);

      $.ajax({
        url: frontend_scripts.ajax_url,
        type: "post",
        data: {
          action: "ta_forms_send_email",
          data: formData,
          form_id: formId,
          nonce: frontend_scripts.nonce,
          userInfo: userData,
        },
        success: function (response) {
          console.log('response', response);
          
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

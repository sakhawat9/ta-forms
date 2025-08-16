/**
 * Table of contents
 * -----------------------------------
 * 01.CURRENT TIME
 * 02.OPEN BUTTON
 * 03.CHECK TERMS AND CONDITION
 * 04.CHECK AVAILABILITY
 * 05.GET WEEK DAY
 * 06.MULTI USER AVAILABILITY
 * 07.MULTI USER SEARCH
 * 08.BUTTONS AVAILABILITY
 * 09.SINGLE CHAT AVAILABILITY
 * 10.ADD SUBJECT OR BODY TEXT
 * 11. SHOW HIDE AUTO OPEN POPUP AFTER SECONDS
 * DARK VERSION
 * -----------------------------------
 */

"use strict";
let wHelp = document.querySelectorAll(".wHelp");
let wHelpMulti = document.querySelectorAll(".wHelp-multi");
let wHelpButton = document.querySelector(".wHelp_button");
let wHelpBubble = document.querySelectorAll(".wHelp-bubble");
let wHelpCurrentTime = document.querySelector(".current-time");
let wHelpUserAvailability = document.querySelectorAll(".wHelpUserAvailability");
let wHelpMultiPopupContent = document.querySelector(
  ".wHelp-multi__popup__content"
);
let wHelpMultiUser = document.querySelectorAll(".user");
let wHelpCheckboxDiv = document.querySelectorAll(".wHelp--checkbox");
let wHelpCheckbox = document.querySelectorAll(".wHelp__checkbox");
let wHelpCheckButton = document.querySelectorAll(".wHelp__send-message");
let wHelpPopupContent = document.querySelectorAll(".wHelp__popup__content");
let autoShowPopup = whatshelp_pro_frontend_script.autoShowPopup;
let autoOpenPopupTimeout = whatshelp_pro_frontend_script.autoOpenPopupTimeout;

let analytics_parameter =
  whatshelp_pro_frontend_script.analytics_parameter.google_analytics_parameter;
let event_name = whatshelp_pro_frontend_script.analytics_parameter.event_name;

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".ta_forms_analytics").forEach((btn) => {
    btn.addEventListener("click", function () {
      let number = this.getAttribute("data-number") || "";
      let group = this.getAttribute("data-group") || "";

      // Deep clone the analytics_parameter to avoid overwriting original
      let eventData = JSON.parse(JSON.stringify(analytics_parameter));

      eventData.forEach((param) => {
        if (param.event_parameter === "number") {
          param.event_parameter_value = number;
        } else if (param.event_parameter === "group") {
          param.event_parameter_value = group;
        }
      });

      // Build GA params object
      let ga_prams = {};
      eventData.forEach((param) => {
        ga_prams[param.event_parameter] = param.event_parameter_value;
      });
      
      // Send GA event
      if (typeof gtag !== "undefined") {
        gtag("event", event_name, ga_prams);
      }
    });
  });
});

/******************** 01.CURRENT TIME  ********************/
let today = new Date();
if (wHelpCurrentTime !== null) {
  let time =
    today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  wHelpCurrentTime.innerText = time;
}
/******************** 02.OPEN BUTTON  ********************/
const openChatBtn = (e) => {
  e.preventDefault();
  wHelp.forEach((item) => {
    item.classList?.toggle("wHelp-show");
  });
  wHelpMulti.forEach((item) => {
    item.classList?.toggle("wHelp-show");
  });
};

wHelpBubble.forEach((item) => {
  if (!wHelpButton) {
    item.addEventListener("click", openChatBtn);
  }
});

// if (alternativeWHelpBubble.length > 0) {
//   const elements = document.querySelectorAll(alternativeWHelpBubble);
//   elements.forEach((item) => {
//     if (!wHelpButton) {
//       item.addEventListener("click", openChatBtn);
//     }
//   });
// }

/******************** 03.CHECK TERMS AND CONDITION  ********************/
const wHelpCheckboxValue =
  localStorage.getItem("wHelpCheckboxValue") === "true";

if (wHelpCheckboxValue) {
  wHelpMultiUser.forEach((user) => {
    if (user?.classList.contains("condition__checked")) {
      user.classList.remove("condition__checked");
    }
  });
  wHelpCheckButton.forEach((itemBtn) => {
    if (itemBtn?.classList.contains("condition__checked")) {
      itemBtn.classList.remove("condition__checked");
    }
  });
  if (wHelpCheckboxDiv) {
    wHelpCheckboxDiv.forEach((item) => {
      item.style.display = "none";
    });
  }
}

wHelpCheckbox?.forEach((itemWrapper) => {
  itemWrapper?.addEventListener("change", function () {
    if (this.checked) {
      wHelpCheckButton.forEach((itemBtn) => {
        if (itemBtn?.classList.contains("condition__checked")) {
          itemBtn.classList.remove("condition__checked");
        }
      });
      if (wHelpUserAvailability) {
        wHelpUserAvailability.forEach((item) => {
          item.classList.remove("condition__checked");
        });
      }
      if (wHelpCheckboxDiv) {
        wHelpCheckboxDiv.forEach((item) => {
          item.style.display = "none";
        });
      }
      localStorage.setItem("wHelpCheckboxValue", this.checked);
    }
  });
});

/******************** 04.CHECK AVAILABILITY  ********************/
function is_available(available, now) {
  let is_available = false;
  let almost_available = false;
  for (let key in available) {
    if (available.hasOwnProperty(key)) {
      if (get_day_of_week(key) == now.day()) {
        let timeRange = available[key].split("-");
        let start = moment.tz(timeRange[0], "HH:mm", now.tz()); // Apply the same timezone
        let end = moment.tz(timeRange[1], "HH:mm", now.tz());

        // Align start/end to the same date as `now`
        start.year(now.year()).month(now.month()).date(now.date());
        end.year(now.year()).month(now.month()).date(now.date());

        if (now.isBetween(start, end)) {
          is_available = true;
        } else if (now.isBefore(start)) {
          almost_available = true;
        }
      }
    }
  }
  return { is_available: is_available, almost_available: almost_available };
}

/******************** 05.GET WEEK DAY  ********************/
function get_day_of_week(name) {
  name = name.toLowerCase();
  if (name == "sunday") {
    return 0;
  } else if (name == "monday") {
    return 1;
  } else if (name == "tuesday") {
    return 2;
  } else if (name == "wednesday") {
    return 3;
  } else if (name == "thursday") {
    return 4;
  } else if (name == "friday") {
    return 5;
  } else if (name == "saturday") {
    return 6;
  }
}

/******************** 06.MULTI USER AVAILABILITY  ********************/
const searchInfo = wHelpMultiPopupContent?.getAttribute("data-search");
const isGrid = document
  .querySelector(".wHelp-multi")
  ?.classList.contains("wHelp-grid");

if (wHelpUserAvailability !== undefined) {
  if (searchInfo === "true") {
    wHelpMultiPopupContent.classList.add("wHelp-search");
  }
  if (wHelpUserAvailability.length > 3 && !isGrid) {
    wHelpMultiPopupContent.classList.add("wHelp-scroll");
  }
  if (wHelpUserAvailability.length > 4 && isGrid) {
    wHelpMultiPopupContent.classList.add("wHelp-scroll");
  }
  wHelpUserAvailability.forEach((item) => {
    const availableTimes = item.getAttribute("data-userAvailability");
    const timezone = item.getAttribute("data-timezone");
    let now = timezone ? moment().tz(timezone) : moment();
    let available = is_available(JSON.parse(availableTimes), now);
    if (available.is_available || availableTimes == null) {
      wHelpUserAvailability.forEach((item) => {
        const availableTime = item.getAttribute("data-userAvailability");
        if (availableTime === availableTimes) {
          item.classList.add("avatar-active");
          item.classList.remove("avatar-inactive");
          item.classList.add("ta_forms_analytics");
        }
      });
    } else {
      wHelpUserAvailability.forEach((item) => {
        const availableTime = item.getAttribute("data-userAvailability");
        if (availableTime === availableTimes) {
          item.classList.add("avatar-inactive");
          item.setAttribute("disabled", "");
          item.classList.remove("avatar-active");
          item.classList.remove("ta_forms_analytics");
        }
      });
    }
  });
}
/******************** 07.MULTI USER SEARCH  ********************/
function searchUser() {
  var searchKeyword,
    i,
    txtValue,
    found = false;
  let input = document.getElementById("search-input");
  let filter = input.value.toUpperCase();
  let wHelpMultiUser = document.getElementById("multi-user");
  let user = wHelpMultiUser.getElementsByClassName("user");
  let noResults = document.querySelector(".no-results");

  for (i = 0; i < user.length; i++) {
    searchKeyword = user[i].getElementsByClassName("user__info--name")[0];
    txtValue = searchKeyword.textContent || searchKeyword.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      user[i].style.display = "";
      found = true;
    } else {
      user[i].style.display = "none";
    }
  }
  // Show "User not found" message if no users were found
  if (found) {
    noResults.style.display = "none";
  } else {
    noResults.style.display = "block";
  }
}

/******************** 08.BUTTONS AVAILABILITY  ********************/
let wHelpButtons = document.querySelectorAll(".wHelpButtons");
if (wHelpButtons !== undefined) {
  wHelpButtons.forEach((item) => {
    const availableTimes = item.getAttribute("data-btnavailablety");
    const timezone = item.getAttribute("data-timezone");
    let now = timezone ? moment().tz(timezone) : moment();
    let available = is_available(JSON.parse(availableTimes), now);
    if (available.is_available) {
      wHelpButtons.forEach((item) => {
        const availableTime = item.getAttribute("data-btnavailablety");
        if (availableTime === availableTimes) {
          item.classList.add("avatar-active");
          item.classList.add("ta_forms_analytics");
          item.classList.remove("avatar-inactive");
        }
      });
    } else {
      wHelpButtons.forEach((item) => {
        const availableTime = item.getAttribute("data-btnavailablety");
        if (availableTime === availableTimes) {
          item.classList.add("avatar-inactive");
          item.classList.remove("ta_forms_analytics");
          item.classList.remove("avatar-active");
        }
      });
    }
  });
}

/******************** 09.SINGLE CHAT AVAILABILITY  ********************/
const chatAvailability = document.querySelector(".chat-availability");

if (chatAvailability) {
  const chatAvailableTime = chatAvailability.getAttribute("data-availability");
  if (chatAvailableTime !== undefined) {
    const timezone = chatAvailability.getAttribute("data-timezone");
    let now = timezone ? moment().tz(timezone) : moment();
    let available = is_available(JSON.parse(chatAvailableTime), now);

    if (available.is_available || chatAvailableTime == null) {
      chatAvailability.classList.add("avatar-active");
      chatAvailability.classList.add("ta_forms_analytics");
      chatAvailability.classList.remove("avatar-inactive");
      wHelpCheckButton.forEach((whatsappForm) => {
        whatsappForm.classList.add("ta_forms_analytics");
        chatAvailability.classList.remove("ta_forms_analytics");
      });
    } else {
      wHelpCheckButton.forEach((whatsappForm) => {
        whatsappForm.classList.remove("ta_forms_analytics");
        chatAvailability.classList.add("ta_forms_analytics");
      });
      chatAvailability.classList.add("avatar-inactive");
      chatAvailability.classList.remove("avatar-active");
      chatAvailability.classList.remove("ta_forms_analytics");
    }
  }
}

/******************** 10.ADD SUBJECT OR BODY TEXT  ********************/
(function ($) {
  if ($("#form").length) {
    $("#form").validate();
  }

  wHelpPopupContent.forEach((whatsappForm) => {
    let submit_btn = whatsappForm.querySelector(".wHelp__send-message");
    $(whatsappForm).submit(function (e) {
      e.preventDefault();

      const form = $(this);
      if (!form.valid()) {
        return;
      }
      wHelpCheckButton.forEach((btn) => {
        if (!btn.classList.contains("condition__checked")) {
          const chatAvailableTime = JSON.parse(
            chatAvailability?.getAttribute("data-availability")
          );
          if (chatAvailableTime) {
            const now = moment();
            const available = is_available(chatAvailableTime, now);
            if (available.is_available) {
              const formData = $(this).serialize();
              const currentUrl = window.location.href;
              const currentTitle = document.title;
              let button = whatsappForm.getAttribute("data-button");
              let loading = whatsappForm.getAttribute("data-loading");
              let product_attr = whatsappForm.getAttribute("data-product_attr");
              // Whatsapp form handler
              $.post(
                frontend_scripts.ajaxurl,
                {
                  action: "handle_form_submission",
                  data: formData,
                  product_id: product_attr,
                  nonce: frontend_scripts.nonce,
                  current_url: currentUrl,
                  current_title: currentTitle,
                },
                (response) => {
                  if (response.success) {
                    submit_btn.innerHTML = loading;
                    setTimeout(function () {
                      window.open(
                        response.data.whatsAppURL,
                        frontend_scripts.open_in_new_tab
                      );
                      form[0].reset();
                      submit_btn.innerHTML = button;
                    }, 2000);
                  } else {
                    alert("Error processing request.");
                  }
                }
              ).fail(() => alert("Unexpected error occurred."));

              // Webhook submission handler
              $.post(
                frontend_scripts.ajaxurl,
                {
                  action: "handle_form_submission_webhook",
                  data: formData,
                  nonce: frontend_scripts.nonce,
                  webhook_url: frontend_scripts.webhook_url,
                  the_title: frontend_scripts.the_title,
                  current_url: window.location.href,
                },
                function (response) {
                  if (response) {
                    var data = response.data;
                    var dynamic_data = data.dynamic_data;
                    var form_data = data.form_data;
                    var webhook_url = data.webhook_url;

                    if (webhook_url) {
                      var hookValues = Array.isArray(dynamic_data)
                        ? dynamic_data
                        : [dynamic_data];
                      var dynamic_data = {};
                      hookValues.forEach((e, index) => {
                        dynamic_data["value" + (index + 1)] = e;
                      });

                      var mergedData = Object.assign(
                        {},
                        form_data,
                        dynamic_data
                      );
                      var mergedData = JSON.stringify(mergedData);

                      $.ajax({
                        url: webhook_url,
                        type: "POST",
                        mode: "no-cors",
                        contentType: "application/json",
                        data: mergedData,
                        success: function (response) {
                          console.log("Webhook Response:", response);
                        },
                      });
                    }
                  }
                }
              ).fail(function () {
                alert("Unexpected error occurred.");
              });
            }
          }
        }
      });
    });
  });

  $(".ta_forms_multi_user").on("click", function () {
    var clickedUser = $(this);
    var userName = clickedUser.find(".user__info--name").text().trim();

    $.post({
      url: frontend_scripts.ajaxurl,
      data: {
        action: "get_multi_user_details",
        the_title: frontend_scripts.the_title,
        current_url: window.location.href,
      },
      success: function (response) {
        if (response.success) {
          let users = response.data.user_data;
          let webhookUrl = response.data.webhook_url;
          var dynamic_data = response.data.dynamic_data;
          let userDetails = users.find((user) => user.name === userName);

          if (userDetails && webhookUrl) {
            let payload = {
              name: userDetails.name,
              phone: userDetails.phone,
            };

            var hookValues = Array.isArray(dynamic_data)
              ? dynamic_data
              : [dynamic_data];
            var dynamic_data = {};
            hookValues.forEach((e, index) => {
              dynamic_data["value" + (index + 1)] = e;
            });

            var mergedData = Object.assign({}, payload, dynamic_data);
            $.ajax({
              url: webhookUrl,
              type: "POST",
              contentType: "application/json",
              data: JSON.stringify(mergedData),
              success: function (webhookResponse) {
                console.log("Webhook Response:", webhookResponse);
              },
            });
          }
        }
      },
    });
  });

  // Function to handle webhook requests
  function sendWebhook(buttonClass, scriptData) {
    let buttons = document.querySelectorAll(buttonClass);
    if (buttons.length > 0) {
      buttons.forEach((button) => {
        if (button) {
          button.addEventListener("click", () => {
            var webhook_url = scriptData.webhook_url;
            var hook_values = scriptData.hook_values;

            if (webhook_url) {
              var hookValues = Array.isArray(hook_values)
                ? hook_values
                : [hook_values];

              var pair_values = {};
              hookValues.forEach((e, index) => {
                pair_values["value" + (index + 1)] = e;
              });

              $.ajax({
                url: webhook_url,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(pair_values),
                success: function (response) {
                  console.log("Webhook Response:", response);
                },
              });
            }
          });
        }
      });
    }
  }

  // Call the function for different button types
  sendWebhook(".ta_forms_link", {
    webhook_url: frontend_scripts.webhook_url,
    hook_values: frontend_scripts.hook_v,
  });

  sendWebhook(".woo_button_webhook", {
    webhook_url: frontend_scripts.woo_webhook_url,
    hook_values: frontend_scripts.woo_string,
  });

  const shortcode_button_webhook = document.querySelectorAll(
    ".shortcode_button_webhook"
  );
  shortcode_button_webhook.forEach((shortcode_button) => {
    if (shortcode_button && typeof shortcode_scripts !== "undefined") {
      sendWebhook(".shortcode_button_webhook", {
        webhook_url: shortcode_scripts.shortcode_webhook_url,
        hook_values: shortcode_scripts.shortcode_string,
      });
    }
  });
})(jQuery);

/******************** 10. SHOW HIDE AUTO OPEN POPUP AFTER SECONDS  ********************/

function authShowPopup() {
  if (autoShowPopup != 0) {
    wHelp.forEach((item) => {
      item.classList?.toggle("wHelp-show");
    });
  }

  if (autoShowPopup != 0) {
    wHelpMulti.forEach((item) => {
      item.classList?.toggle("wHelp-show");
    });
  }
}

if (autoOpenPopupTimeout > 0) {
  setTimeout(authShowPopup, autoOpenPopupTimeout);
} else {
  authShowPopup();
}

$(document).ready(function() {
  $("#nav-item-settings").addClass('active');

  addEventListeners();
});

function addEventListeners() {
  $(".update-button").on("keyup", function () {
    var buttonID = $(this).attr("data-button-id");
    enableButtonFromInput($(buttonID), this);
  });

  $(".update-password-input").on("keyup", updatePasswordActions);
}

// enables-disables a button based on if the specified input is empty
function enableButtonFromInput(button, input) {
  var inputLength = $(input).val().length;

  if (inputLength > 0) {
    $(button).prop('disabled', false); // set to enabled
  }
  else {
    $(button).prop('disabled', true); // set to disabled
  }
}

// disables or enables the save password button based on some conditions
function updatePasswordActions() {
  $("#new-password-btn").prop('disabled', false);
  $("#confirm-password").closest(".form-group").find(".passwords-must-match-text").addClass("d-none");

  $(".update-password-input").removeClass("red");

  // check if all three inputs have values
  if (($("#old-password")).val().length == 0 || $("#new-password").val().length == 0 || $("#confirm-password").val().length == 0) {
    $("#new-password-btn").prop('disabled', true);
  }

  // check if new and confirm passwords match
  else if ($("#new-password").val() != $("#confirm-password").val()) {
    $("#new-password-btn").prop('disabled', true);
    $("#confirm-password").closest(".form-group").find(".passwords-must-match-text").removeClass("d-none");

    $("#new-password").addClass("red");
    $("#confirm-password").addClass("red");
  }
}

// checks if an input is empty
function isInputEmpty(input) {
  var length = $(input).length;
  if (length == 0)
    return true;
  else
    return false;
}

// clears the password inputs and disables the save button
function clearPasswordInputs() {
  $(".update-password-input").val('');
  updatePasswordActions();
  $("#new-password-btn").prop('disabled', true);
}









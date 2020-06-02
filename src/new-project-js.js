 $(document).ready(function() {
  enableFlatpickrDates();
  $("#nav-item-new-project").addClass('active');
  autosize($('textarea'));
  $(".form-control").on('change keyup', updateCreateProjectButton);

});

// enables the flatpickr library scripts
function enableFlatpickrDates() {
  flatpickr("#date-due", {
   enableTime: true,
   dateFormat: "Y-m-d H:i",
   altInput: true,
   altFormat: "F j, Y H:i",
 });
}

// clears the inputs when the reset button is clicked
function clearInputs() {
  $(".form-control").val('');
  updateCreateProjectButton();
}

// enables-disables the create project button when user updates the values in the inputs
function updateCreateProjectButton() {
  var nameLength = $("#new-project-name").val().length;
  var dateDueLength = $("#date-due").val().length;

  if (nameLength != 0 && dateDueLength != 0) {
    $("#create-project-btn").prop('disabled', false);
  } else {
    $("#create-project-btn").prop('disabled', true);
  }
}



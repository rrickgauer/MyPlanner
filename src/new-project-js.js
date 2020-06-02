 $(document).ready(function() {
  enableFlatpickrDates();
  $("#nav-item-new-project").addClass('active');
  autosize($('textarea'));
});


function enableFlatpickrDates() {
  flatpickr("#date-due", {
   enableTime: true,
   dateFormat: "Y-m-d H:i",
   altInput: true,
   altFormat: "F j, Y H:i",
 });
}
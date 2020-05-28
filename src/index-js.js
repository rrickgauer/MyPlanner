const PHP_FILE = 'index-backend.php';

$(document).ready(function() {
  getUserProjects();                        // get all the projects
  $("#nav-item-home").addClass("active");   // set the navbar link to active

  addEventListeners();
});


// adds all the event listeners
function addEventListeners() {

  $("#project-search-input").on("keyup", function() {
    getUserProjects($(this).val());
  });

}

// gets the project cards from the server and displays them on success
function getUserProjects(query = '') {
  var data = {
    function: 'get-projects',
    query: query,
  };

  $.get(PHP_FILE, data, function(response) {
    displayProjects(JSON.parse(response));
  });
}

// displays all the project cards 
function displayProjects(data) {
  var html = '';  // empty html

  // create html for the project cards
  for (var count = 0; count < data.length; count++) {    
    var id              = data[count].id;
    var name            = data[count].name;
    var dateDue         = data[count].date_due_display_date;
    var timeDue         = data[count].timeDue;
    var countChecklists = data[count].count_checklists;
    var countItems      = data[count].count_items;
    var countNotes      = data[count].count_notes;

    html += getProjectCard(id, name, dateDue, timeDue, countChecklists, countItems, countNotes);
  }

  // set the new html
  $("#projects .row").html(html);
}

// returns the html for a project card
function getProjectCard(id, name, dateDue, timeDue, countChecklists, countItems, countNotes) {
  var html = '';
  html += '<div class="col"><div class="card card-project" data-project-id="' + id + '">';
  html += '<div class="card-header"><h5>' + name + '</h5></div>';
  html += '<div class="card-body">';
  html += '<span class="badge badge-secondary mr-2">' + countItems + '&nbsp;items</span>';
  html += '<span class="badge badge-secondary mr-2">' + countChecklists + '&nbsp;checklists</span>';
  html += '<span class="badge badge-secondary mr-2">' + countNotes + '&nbsp;notes</span>';
  html += '<div class="card-footer">';
  html += '<div class="card-project-date">' + dateDue + '</div>';
  html += '<a href="project.php?projectID=' + id + '">View</a>';
  html += '</div>';
  html += '</div></div></div>';

  return html;
}
const PHP_FILE = 'index-backend.php';
const PROJECT_SORTING_OPTIONS = {
  NAME_ASC        : 'name_asc',
  NAME_DESC       : 'name_desc',
  DATE_CREATED_NEW: 'date_created_new',
  DATE_CREATED_OLD: 'date_created_old',
  DATE_DUE_NEW    : 'date_due_new',
  DATE_DUE_OLD    : 'date_due_old'
}

const PROJECT_VIEW_OPTIONS = {
  CARD: 'card',
  TABLE: 'table',
}

var projectSorting = PROJECT_SORTING_OPTIONS.NAME_ASC;  // initial item sorting display
var projectView = PROJECT_VIEW_OPTIONS.CARD;            // initial item view display

$(document).ready(function() {
  getUserProjects();                        // get all the projects
  $("#nav-item-home").addClass("active");   // set the navbar link to active
  addEventListeners();
});


// adds all the event listeners
function addEventListeners() {

  // search for projects from input
  $("#project-search-input").on("keyup", function() {
    getUserProjects($(this).val());
  });

  // switch project card sorting
  $(".project-sorting-option").on("click", function() {
    updateProjectSorting(this);
  });


  // switch projects view
  $(".project-view-option").on('click', function() {
    updateProjectView(this);
  });
}


function updateProjectSorting(btn) {
  // add fade out animation
  $(".card-project").addClass("animate__animated animate__fadeOut");


  // add active class to clicked button
  $(".project-sorting-option").removeClass("active");
  $(btn).addClass("active");

  // update projectSorting
  switch($(btn).attr('data-project-sorting')) {
    case "name_desc":
      projectSorting = PROJECT_SORTING_OPTIONS.NAME_DESC;
      break;
    case "date_created_new": 
      projectSorting = PROJECT_SORTING_OPTIONS.DATE_CREATED_NEW;
      break;
    case "date_created_old":
      projectSorting = PROJECT_SORTING_OPTIONS.DATE_CREATED_OLD;
      break;
    case "date_due_new":
      projectSorting = PROJECT_SORTING_OPTIONS.DATE_DUE_NEW;
      break;
    case "date_due_old":
      projectSorting = PROJECT_SORTING_OPTIONS.DATE_DUE_OLD;
      break;
    default:
      projectSorting = PROJECT_SORTING_OPTIONS.NAME_ASC;
      break;
  }

  // send get the projects in new sorted order
  getUserProjects($("#project-search-input").val());
}


function updateProjectView(btn) {
  // add active class to clicked button
  $(".project-view-option").removeClass("active");
  $(btn).addClass("active");

  // update projectView value to selected value
  if ($(btn).attr('data-project-view') == PROJECT_VIEW_OPTIONS.CARD) {
    projectView = PROJECT_VIEW_OPTIONS.CARD;
  } else {
    projectView = PROJECT_VIEW_OPTIONS.TABLE;
  }

  // update the project cards
  getUserProjects($("#project-search-input").val());
}


// gets the project cards from the server and displays them on success
function getUserProjects(query = '') {
  var data = {
    function: 'get-projects',
    query: query,
    sort: projectSorting,
  };

  $.get(PHP_FILE, data, function(response) {
    if (projectView == PROJECT_VIEW_OPTIONS.CARD)
      displayProjectCards(JSON.parse(response));
    else
      displayProjectTable(JSON.parse(response));
  });
}

// displays all the project cards 
function displayProjectCards(projects) {
  var html = '<div class="card-deck mb-3">';       // empty html

  for (var count = 0; count <  projects.length; count++) {
    if (count % 3 == 0)                       // create new card deck
      html += '</div><div class="card-deck mt-3">';
    
    html += getProjectCardHtml(projects[count]);  // generated project card
  }

  html += '</div>';                           // close card deck

  // set the new html
  $("#project-cards").html(html);
}

function getProjectCardHtml(project) {
  var html = '';
  html += '<div class="card card-project" data-project-id="' + project.id + '">';
  html += '<div class="card-header"><h5>' + project.name + '</h5></div>';
  html += '<div class="card-body">';
  html += '<span class="badge badge-secondary mr-2">' + project.count_items + '&nbsp;items</span>';
  html += '<span class="badge badge-secondary mr-2">' + project.count_checklists + '&nbsp;checklists</span>';
  html += '<span class="badge badge-secondary mr-2">' + project.count_notes + '&nbsp;notes</span>';
  html += '<div class="card-footer">';

  var dateDueDisplay = getDisplayDateValue(project.date_due_display_date, project.date_due_display_time);
  html += '<div class="card-project-date"><b>Due:&nbsp</b>' + dateDueDisplay + '</div>';

  html += '<a href="project.php?projectID=' + project.id + '">View</a>';
  html += '</div>';
  html += '</div></div>';

  return html;
}

// displays the projects as a table
function displayProjectTable(projects) {
  var html = getProjectTableHtml(projects);
  $("#project-cards").html(html);
}

// returns the project table html
function getProjectTableHtml(projects) {
  var html = '<div class="table-responsive"><table class="table table-hover mt-1">';
  html += '<thead><tr>';
  html += '<th>Name</th>';
  html += '<th>Items</th>';
  html += '<th>Checklists</th>';
  html += '<th>Notes</th>';
  html += '<th>Date created</th>';
  html += '<th>Date due</th>';
  html += '<th>Page Link</th>';
  html += '</tr></thead><tbody>';

  // generate table rows
  const size = projects.length;
  for (var count = 0; count < size; count++) {
    html += getProjectTableRowHtml(projects[count]);
  }

  html += '</tbody></table></div>';

  return html;
}

// return the html of a project table row
function getProjectTableRowHtml(project) {
  var html = '<tr data-project-id="' + project.id + '">';
  html += '<td>' + project.name + '</td>';
  html += '<td>' + project.count_items + '</td>';
  html += '<td>' + project.count_checklists + '</td>';
  html += '<td>' + project.count_notes + '</td>';

  var dateCreatedDisplay = getDisplayDateValue(project.date_created_display_date, project.date_created_display_time);
  html += '<td>' + dateCreatedDisplay + '</td>';

  var dateDueDisplay = getDisplayDateValue(project.date_due_display_date, project.date_due_display_time);
  html += '<td>' + dateDueDisplay + '</td>';

  html += '<td><a href="project.php?projectID=' + project.id + '"><i class="bx bx-link"></i></a></td>';
  html +='</tr>';

  return html;
}

// returns the corrent display of a date in the project card or table
function getDisplayDateValue(newDate, newTime) {
  var display = 'n/a';
  if (newDate != null) {
    display = newDate;
    if (newTime != null) {
      display += ' at ' + newTime;
    }
  }

  return display;
}
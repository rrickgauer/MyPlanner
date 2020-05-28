const PHP_FILE = 'index-backend.php';

const PROJECT_SORTING_OPTIONS = {
  NAME_ASC        : 'name_asc',
  NAME_DESC       : 'name_desc',
  DATE_CREATED_NEW: 'date_created_new',
  DATE_CREATED_OLD: 'date_created_old',
  DATE_DUE_NEW    : 'date_due_new',
  DATE_DUE_OLD    : 'date_due_old'
}

var projectSorting = PROJECT_SORTING_OPTIONS.NAME_ASC; // initial item sorting display

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

// gets the project cards from the server and displays them on success
function getUserProjects(query = '') {
  var data = {
    function: 'get-projects',
    query: query,
    sort: projectSorting,
  };

  $.get(PHP_FILE, data, function(response) {
    displayProjects(JSON.parse(response));
  });
}

// displays all the project cards 
function displayProjects(projects) {
  var html = '<div class="card-deck mb-3">';       // empty html

  for (var count = 0; count <  projects.length; count++) {
    if (count % 3 == 0)                       // create new card deck
      html += getCardDeckHtml();
    
    html += getProjectCard(projects[count]);  // generated project card
  }

  html += '</div>';                           // close card deck

  // set the new html
  $("#project-cards").html(html);
}

function getProjectCard(project) {
  var html = '';
  html += '<div class="card card-project" data-project-id="' + project.id + '">';
  html += '<div class="card-header"><h5>' + project.name + '</h5></div>';
  html += '<div class="card-body">';
  html += '<span class="badge badge-secondary mr-2">' + project.count_items + '&nbsp;items</span>';
  html += '<span class="badge badge-secondary mr-2">' + project.count_checklists + '&nbsp;checklists</span>';
  html += '<span class="badge badge-secondary mr-2">' + project.count_notes + '&nbsp;notes</span>';
  html += '<div class="card-footer">';
  html += '<div class="card-project-date">' + project.date_due_display_date + '</div>';
  html += '<a href="project.php?projectID=' + project.id + '">View</a>';
  html += '</div>';
  html += '</div></div>';

  return html;
}

function getCardDeckHtml() {
  return '</div><div class="card-deck mb-3">';
}


function printProjectSorting() {
  console.log(projectSorting);
}
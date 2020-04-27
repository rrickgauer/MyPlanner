// const queryString = window.location.search;
// const urlParams = new URLSearchParams(queryString);
// const projectID = urlParams.get('projectID'); // url parameters

const phpFile = 'index-backend.php';

$(document).ready(function() {

  // get all the projects
  getUserProjects();




});


function getUserProjects() {
  $.ajax({  
    type: "GET",
    url: phpFile,
    data: {
      function: 'getProjects',
    },

    success: function(response) {
      displayProjects(JSON.parse(response));
      console.log(JSON.parse(response));
    },
  });
}




function displayProjects(data) {

  // empty html
  html = '';

  // create html for the project cards
  for (var count = 0; count < data.length; count++) {    
    const id              = data[count].id;
    const name            = data[count].name;
    const dateDue         = data[count].date_due_display_date;
    const timeDue         = data[count].timeDue;
    const countChecklists = data[count].count_checklists;

    if (count % 3 == 0) {
      // html += '</div><div class="row row-cols-1 row-cols-md-3">';
    }

    html += getProjectCard(id, name, dateDue, timeDue, countChecklists);
  }

  // set the new html
  $("#projects div").html(html);
}


function getProjectCard(id, name, dateDue, timeDue, countChecklists) {

  html = '';
  html += '<div class="card card-project" data-project-id="' + id + '">';
  html += '<div class="card-header"><h5>' + name + '</h5></div>';
  html += '<div class="card-body">';
  html += '<span class="badge badge-secondary">' + countChecklists + '&nbsp;checklists</span>';
  html += '<div class="card-footer">';
  html += '<div class="card-project-date">' + dateDue + '</div>';
  html += '<a href="project.php?projectID=' + id + '">View</a>';
  html += '</div>';
  html += '</div></div>';

  return html;
}
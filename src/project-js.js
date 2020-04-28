const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const projectID = urlParams.get('projectID');

$(document).ready(function() {
  getChecklists();
  getProjectItems();
  $("#new-project-name").on("keyup", updateRenameProjectButton);
  $("#new-checklist-name").on("keyup", updateNewChecklistButton);
});

// executes addTodoItem when enter is pressed
$(document).on("keypress", "#new-project-checklist-item-input", function(e) {
  if (e.keyCode == 13) {
    e.preventDefault();
    addChecklistItem();
  }
});


// insert a new checklist item
function addChecklistItem() {
  var content = $("#new-project-checklist-item-input").val();
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');

  $.ajax({
    type: "POST",
    url: 'project-backend.php',
    data: {
      "checklistID": checklistID,
      "content": content
    },

    success: function(response) {
      var data = JSON.parse(response);

      // get the updated data
      displayChecklistItems(data);

      // clear the input
      $("#new-project-checklist-item-input").val('');

    },
  });
}


// opens a checklist modal from the seletced option on the sidebar
function openProjectChecklist(checklist) {

  // get the id of the sidebar item selected
  const checklistID = $(checklist).data("id");

  // add this id to the modal
  $("#project-checklist-modal").attr('data-checklist-id', checklistID);

  // retrieve the new checklist items
  getChecklistItems(checklistID);

  // set the modal title to the list name
  var listName = $(checklist).html();
  $("#project-checklist-modal .modal-title").html(listName);

  // show the checklist
  $('#project-checklist-modal').modal('show');
}

// clears the new checklist input in the modal
function clearNewChecklistInput() {
  $("#new-checklist-name").val('');
}


// retrieves the checklist modal from the server
function getChecklistItems(checklistID) {
  $.ajax({
    type: "GET",
    url: 'project-backend.php',
    data: {
      "checklistID": checklistID,
      "data": 'items',
    },

    success: function(response) {
      var data = JSON.parse(response);
      displayChecklistItems(data);
      // console.log(data);
    }
  });
}

// displays the retrieved checklist modal
function displayChecklistItems(data) {
  const size = data.length;
  var html = '';

  // create new table html
  for (var count = 0; count < size; count++) 
    html += getChecklistTableRow(data[count].id, data[count].content, data[count].completed);

  // set the table to the new html
  $("#project-checklist-modal-items tbody").html(html);

  }

// gets the html to be inserted for a project checklist item row in the modal
function getChecklistTableRow(id, content, completed) {
  var tr = '';
  tr += '<tr data-project-checklist-item-id="' + id + '">';

  if (completed == 'y') {
    tr += '<td><input type="checkbox" class="project-checklist-item-checkbox" onclick="updateChecklistItem(this)" checked></td>';
    tr += '<td><span class="completed">' + content + '</span></td>';
  }

  else {
    tr += '<td><input type="checkbox" class="project-checklist-item-checkbox" onclick="updateChecklistItem(this)"></td>';
    tr += '<td>' + content + '</td>';
  }

  tr += '<td><i class="bx bx-trash pointer" onclick="deleteChecklistItem(' + id + ')"></i></td>';
  tr += '</tr>';
  return tr;
}

// activates the sidebar open or close
function activateSidebar() {
  $('#sidebar').toggleClass('active');
}


// creates a new project checklist
function createChecklist() {

  // get the name of the checklist
  var name = $("#new-checklist-name").val();
  const projectID = urlParams.get('projectID');

  $.ajax({
    type: "POST",
    url: 'project-backend.php',
    data: {
      "projectID": projectID,
      "name": name,
    },

    success: function(response) {
      var data = JSON.parse(response);
      setChecklistSidebar(data);
    }
  });

  $('#new-checklist-modal').modal('hide')
  clearNewChecklistInput();
}

// displays the sidebar project checklist data
function setChecklistSidebar(data) {
  const size = data.length;
  var html = '';

  // create html to insert into the sidebar
  for (var count = 0; count < size; count++)  {
    html += '<li onclick="openProjectChecklist(this)" data-id="' + data[count].id + '">' + data[count].name;    
    html += '<span class="badge badge-secondary">' + data[count].itemCount + '</span>';
    html += '</li>';
  }

  // set the html
  $("#sidebar ul").html(html);
}

// retrieves the project checklists from the server
function getChecklists() {
  $.ajax({
    type: "GET",
    url: 'project-backend.php',
    data: {
      "projectID": projectID
    },

    success: function(response) {
      setChecklistSidebar(JSON.parse(response));
    }
  });
}

// updates the selected project checklist modal title
function setProjectChecklistModalTitle(title) {
  $("#project-checklist-modal .modal-title").html(title);
}

// deletes a checklist
function deleteChecklist() {

  if (confirm('Are you sure you want to delete?')) {
    var checklistID = $("#project-checklist-modal").attr('data-checklist-id');

    $.ajax({
      type: "POST",
      url: 'project-backend.php',
      data: {
        "checklistID": checklistID,
        "projectID": projectID,
        "action": 'delete',
      },

      success: function(response) {
        getChecklists();
        $('#project-checklist-modal').modal('hide');
      }
    });
  }
}

function deleteChecklistItem(checklistItemID) {
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');
  $.ajax({
    type: "POST",
    url: 'project-backend.php',
    data: {
      "projectChecklistItemID": checklistItemID,
      "checklistID": checklistID,
      "action": 'delete',
    },

    success: function(response) {
      displayChecklistItems(JSON.parse(response));
    }
  });
}


function updateChecklistItem(checkbox) {
  var tr = $(checkbox).closest("tr");
  var id = $(tr).data("project-checklist-item-id");

  if (checkbox.checked)
    setChecklistItemComplete(id);
  else
    setChecklistItemIncomplete(id);
}

function setChecklistItemIncomplete(checklistItemID) {
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');
  $.ajax({
    type: "POST",
    url: 'project-backend.php',
    data: {
      "projectChecklistItemID": checklistItemID,
      "checklistID": checklistID,
      "action": 'incomplete',
    },

    success: function(response) {
      displayChecklistItems(JSON.parse(response));
    }
  });
}

function setChecklistItemComplete(checklistItemID) {
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');
  $.ajax({
    type: "POST",
    url: 'project-backend.php',
    data: {
      "projectChecklistItemID": checklistItemID,
      "checklistID": checklistID,
      "action": 'complete',
    },

    success: function(response) {
      displayChecklistItems(JSON.parse(response));
    }
  });
}

function deleteProject() {
  if (confirm('Are you sure you want to delete this project?'))
    window.location.href = 'delete-project.php?projectID=' + projectID;
}

function updateRenameProjectButton() {
  var newProjectName = $("#new-project-name").val();

  if (newProjectName.length > 0) {
    $("#new-project-name-btn").prop('disabled', false);
  } else {
    $("#new-project-name-btn").prop('disabled', true);
  }
}

function updateNewChecklistButton() {
  var newChecklistName = $("#new-checklist-name").val();

  if (newChecklistName.length > 0) {
    $("#new-checklist-modal-btn").prop('disabled', false);
  } else {
    $("#new-checklist-modal-btn").prop('disabled', true);
  }
}


function newProjectItem() {
  const itemName = $("#new-item-name").val();
  const itemDescription = $("#new-item-description").val();

  $.ajax({
    type: "POST",
    url: 'project-backend-items.php',
    data: {
      "function": 'insert-item',
      "projectID": projectID,
      "name": itemName,
      "description": itemDescription,
    },

    success: function(response) {
      // console.log(JSON.parse(response));
      console.log(response);
    }
  });
}


function getProjectItems() {
  $.ajax({
    type: "GET",
    url: 'project-backend-items.php',
    data: {
      "function": 'get-items',
      "projectID": projectID,
    },

    success: function(response) {
      console.log(JSON.parse(response));
      // console.log(response);
      displayProjectItems(JSON.parse(response));
    }
  });
}

function displayProjectItems(data) {

  const size = data.length;

  var html = '';

  for (var count = 0; count < size; count++) {
    html += getProjectItemCardHTML(data[count]);
  }

  $("#items-deck").html(html);
}


function getProjectItemCardHTML(item) {

  html = '';

  // main card
  html += '<div class="col"><div class="card item-card" data-item-id="' + item.id + '">';

  // card header
  html += '<div class="card-header"><div class="left">';
  html += '<h5>' + item.name + '</h5>';
  html += '<div class="date-created">' + item.date_created_date + ' &bull; ' + item.date_created_time + '</div></div>';

  // card header right side
  html += '<div class="right">';
  if (item.completed == 'y')
    html += '<i class="bx bx-check-circle"></i>';
  else 
    html += '<i class="bx bx-check-circle hidden"></i>';
  html += '</div></div>';

  // card body
  html += '<div class="card-body item-card-stats"><div class="row">';  

  // date due
  if (item.date_due != null) {
    html += '<div class="card"><div class="card-body">';
    html += 'Date due: ' + item.date_due_date;
    html += '</div></div>';
  }

  // checklists count
  html += '<div class="card"><div class="card-body">';
  html += item.count_checklists + ' checklists';
  html += '</div></div>';

  // notes count
  html += '<div class="card"><div class="card-body">';
  html += item.count_notes + ' notes';
  html += '</div></div>';

  // labels
  html += '<div class="card"><div class="card-body">';
  html += 'labels';
  html += '</div></div>';

  html += '</div></div>';


  // card footer
  html += '<div class="card-footer">';

  // activate item modal
  html += '<button type="button" class="btn btn-primary float-right">View</button>';
  html += '</div>';


  html += '</div></div>';

  return html;

}






















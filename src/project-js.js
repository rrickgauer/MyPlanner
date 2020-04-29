const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const projectID = urlParams.get('projectID');

$(document).ready(function() {
  getChecklists();
  getProjectItems();
  $("#new-project-name").on("keyup", updateRenameProjectButton);
  $("#new-checklist-name").on("keyup", updateNewChecklistButton);

  $("#new-project-checklist-name").on("keyup", updateNewProjectChecklistButton);
  $("#new-project-checklist-btn").on("click", addProjectChecklist);




  // $("#item-modal").modal('show');


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


function getProjectItems() {
  $.ajax({
    type: "GET",
    url: 'project-backend-items.php',
    data: {
      "function": 'get-items',
      "projectID": projectID,
    },

    success: function(response) {
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
  html += '<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#item-modal" onclick="openItemModal(' + item.id + ')">View</button>';
  html += '</div>';

  html += '</div></div>';

  return html;

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
      console.log(JSON.parse(response));

      var item = JSON.parse(response);
      setItemModalData(item[0]);
      getItemChecklistSidebar(item[0].id);
      getProjectItems();

      // display the modal
      $('#item-modal').modal('show');
    }
  });
}


function openItemModal(itemID) {
  $.ajax({
    type: "GET",
    url: 'project-backend-items.php',
    data: {
      "function": 'get-item',
      "itemID": itemID,
    },

    success: function(response) {
      var item = JSON.parse(response);
      setItemModalData(item[0]);

      getItemChecklistSidebar(itemID);

      $('#item-modal').modal('show');
    }
  });
}

function setItemModalData(item) {
  // set the title
  $("#item-modal .modal-title").html(item.name);

  // set the modal data-id
  $("#item-modal").attr("data-item-id", item.id);
}


function updateNewProjectChecklistButton() {
  var newProjectChecklistName = $("#new-project-checklist-name").val();

  if (newProjectChecklistName.length > 0) {
    $("#new-project-checklist-btn").prop('disabled', false);
  } else {
    $("#new-project-checklist-btn").prop('disabled', true);
  }
}


function addProjectChecklist() {
  var itemID = $("#item-modal").attr('data-item-id');
  var checklistName = $("#new-project-checklist-name").val();

  $.ajax({
    type: "POST",
    url: 'project-backend-items.php',
    data: {
      "function": 'insert-item-checklist',
      "itemID": itemID,
      "checklistName": checklistName,
    },

    success: function(response) {
      setItemChecklistSidebar(JSON.parse(response));
    }
  });
}


// populate the item modal checklist sidebar 
function getItemChecklistSidebar(itemID) {
  $.ajax({
    type: "GET",
    url: 'project-backend-items.php',
    data: {
      "function": 'get-item-checklists',
      "itemID": itemID,
    },

    success: function(response) {
      var checklists = JSON.parse(response);
      setItemChecklistSidebar(checklists);
    }
  });
}




function setItemChecklistSidebar(checklists) {
  var html = '';

  // get the html of all the checklist sidebar items
  for (var count = 0; count < checklists.length; count++) {
    html += getItemChecklistSidebarHtml(checklists[count]);
  }

  // set the html 
  $("#item-modal #item-pills-checklists .sidebar-item-checklists").html(html);

}

// the html for a item checklist sidebar item
function getItemChecklistSidebarHtml(checklist) {
  html = '';
  html += '<li class="nav-item dropright" data-item-checklist-id="' + checklist.id + '">';
  html += '<a class="nav-link" data-toggle="dropdown" href="#" role="button">' + checklist.name + '</a>';
  html += '<div class="dropdown-menu">';
  html += '<button class="dropdown-item" type="button" onclick="openItemChecklist(' + checklist.id + ')">Open</button>';
  html += '<button class="dropdown-item" type="button">Close</button>';
  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button">Move up</button>';
  html += '<button class="dropdown-item" type="button">Move down</button>';
  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button">Delete</button>';
  html += '</div>';
  html += '</li>';

  return html;
}




function openItemChecklist(itemChecklistID) {

  $.ajax({
    type: "GET",
    url: 'project-backend-items.php',
    data: {
      "function": 'get-item-checklist-items',
      "itemChecklistID": itemChecklistID,
    },

    success: function(response) {
      console.log(JSON.parse(response));

      var html = getItemChecklistCardHtml(JSON.parse(response));
      $("#item-checklists").append(html);

    }
  });

}



function getItemChecklistCardHtml(data) {

  var html = '';

  html += '<div class="card item-checklist">';
  html += '<div class="card-header">';
  html += '<h6>Checklist name</h6>';
  html += '<div class="dropdown">';
  html += '<button class="btn" type="button" data-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded"></i></button>';
  html += '<div class="dropdown-menu">';
  html += '<a class="dropdown-item" href="#">Close</a>';
  html += '<a class="dropdown-item" href="#">Rename</a>';
  html += '<a class="dropdown-item" href="#">Mark all incomplete</a>';
  html += '</div>';
  html += '</div>';
  html += '</div>';
  html += '<div class="card-body">';
  html += '<div class="input-group mb-3">';
  html += '<input type="text" class="form-control">';
  html += '<div class="input-group-append">';
  html += '<button class="btn btn-outline-secondary" type="button" id="button-addon2">+</button>';
  html += '</div>';
  html += '</div>';
  html += '<ul class="list-group list-group-flush">';

  // get the html for the checklist items
  for (var count = 0; count < data.length; count++) {
    html += getItemChecklistCardBodyHtml(data[count]);
  }

  html += '</ul>';
  html += '</div>';
  html += '</div>';

  return html;
}


function getItemChecklistCardBodyHtml(itemChecklistItem) {

  var html = '';


  html += '<li class="list-group-item" data-item-checklist-item-id="' + itemChecklistItem.id + '">';
  
  // left portion: checkbox and content
  html += '<div class="left">';

  // item is completed
  if (itemChecklistItem.completed == 'n') {
    html += '<input class="form-check-input position-static" type="checkbox">';
    html += '<div class="content">' + itemChecklistItem.content + '</div>';

    // item is incomplete
  } else {
    html += '<input class="form-check-input position-static" type="checkbox" checked>';
    html += '<div class="content completed">' + itemChecklistItem.content + '</div>';
  }
  html += '</div>';


  // right portion: dropdown menu
  html += '<div class="right">';


  // dropdown button
  html += '<div class="dropleft">';
  html += '<button class="btn" type="button" data-toggle="dropdown">';
  html += '<i class="bx bx-dots-vertical-rounded"></i>';
  html += '</button>';

  // dropdown menu
  html += '<div class="dropdown-menu">';
  html += '<button class="dropdown-item" type="button">Edit</button>';
  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button">Move up</button>';
  html += '<button class="dropdown-item" type="button">Move down</button>';
  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button">Delete</button>';
  html += '</div>';


  // closing tags
  html += '</div>';
  html += '</div>';
  html += '</li>';

  return html;
}



















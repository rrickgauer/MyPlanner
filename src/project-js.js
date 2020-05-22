const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const projectID = urlParams.get('projectID');
const backendItemUrl = 'project-backend-items.php';

$(document).ready(function() {
  getChecklists();
  getProjectItems();
  addEventListeners();
});

function addEventListeners() {

  $("#new-item-checklist-btn").on("click", addItemChecklist);
  $("#item-modal").on("hide.bs.modal", closeItemModal);

  // disable a button if an input is empty
  $(".update-button").on("keyup", function() {
    var buttonID = $(this).attr("data-button-id");
    enableButtonFromInput($(buttonID), this)
  });
}


function enableButtonFromInput(button, input) {
  var inputLength = $(input).val().length;

  if (inputLength > 0) {
    $(button).prop('disabled', false); // set to enabled
  } else {
    $(button).prop('disabled', true);  // set to disabled
  }
}


function closeItemModal() {

  // reload the project item cards
  getProjectItems();

  // clear modal html
  $("#item-modal .modal-body .sidebar-item-checklists").html('');
  $("#item-checklists").html('');
  $('#item-modal .modal-title').html('');

  // clear the item notes section
  clearItemNotesSection();


}

// displays a toast alert
function toastAlert(text) {
  $.toast({
    text: text,
    position: 'bottom-center',
    loader: false,
    bgColor: '#3D3D3D',
    textColor: 'white'
  });
}


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


// updates a project checklist item
function updateChecklistItem(checkbox) {
  var tr = $(checkbox).closest("tr");
  var id = $(tr).data("project-checklist-item-id");

  if (checkbox.checked)
    setChecklistItemComplete(id);
  else
    setChecklistItemIncomplete(id);
}

// sets a project checklist item to incomplete
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

// sets a project checklist item to complete
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

// deletes a project
// see delete-project.php
function deleteProject() {
  if (confirm('Are you sure you want to delete this project?'))
    window.location.href = 'delete-project.php?projectID=' + projectID;
}

// returns the project items
function getProjectItems() {
  $.ajax({
    type: "GET",
    url: backendItemUrl,
    data: {
      "function": 'get-items',
      "projectID": projectID,
    },

    success: function(response) {
      displayProjectItems(JSON.parse(response));
    }
  });
}

// displays a project item
function displayProjectItems(data) {

  const size = data.length;

  var html = '';

  for (var count = 0; count < size; count++) {
    html += getProjectItemCardHTML(data[count]);
  }

  $("#items-deck").html(html);
}

// returns the html for a project item card
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
  html += '<div class="card-body item-card-stats">';  

  // date due
  html += '<div class="card item-card-stat"><div class="card-body">';
  html += '<div class="left"><i class="bx bx-time"></i></div>';
  html += '<div class="right"><div class="description">Date due</div><div class="data">' + item.date_due_date + '</div></div>';
  html += '</div></div>';  

  // checklists count
  html += '<div class="card item-card-stat"><div class="card-body">';
  html += '<div class="left"><i class="bx bx-list-check"></i></div>';
  html += '<div class="right"><div class="description">Checklists</div><div class="data">' + item.count_checklists + '</div></div>';
  html += '</div></div>';  

  // notes count
  html += '<div class="card item-card-stat"><div class="card-body">';
  html += '<div class="left"><i class="bx bx-note"></i></div>';
  html += '<div class="right"><div class="description">Notes</div><div class="data">' + item.count_notes + '</div></div>';
  html += '</div></div>';  

  // labels
  html += '<div class="card item-card-stat"><div class="card-body">';
  html += '<div class="left"><i class="bx bx-purchase-tag"></i></div>';
  html += '<div class="right"><div class="description">Labels</div><div class="data">' + '</div></div>';
  html += '</div></div>';  


  html += '</div>';


  // card footer
  html += '<div class="card-footer">';

  // activate item modal
  html += '<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#item-modal" onclick="openItemModal(' + item.id + ')">View</button>';
  html += '</div>';

  html += '</div></div>';

  return html;

}

// inserts a new project item into the database
function newProjectItem() {
  const itemName = $("#new-item-name").val();
  const itemDescription = $("#new-item-description").val();

  $.ajax({
    type: "POST",
    url: backendItemUrl,
    data: {
      "function": 'insert-item',
      "projectID": projectID,
      "name": itemName,
      "description": itemDescription,
    },

    success: function(response) {
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
    url: backendItemUrl,
    data: {
      "function": 'get-item',
      "itemID": itemID,
    },

    success: function(response) {
      var item = JSON.parse(response);
      setItemModalData(item[0]);
      getItemChecklistSidebar(itemID);
      getAllOpenItemChecklists(itemID);
      getItemNotes(itemID);
      $('#item-modal').modal('show');
    }
  });
}

function loadItemModalChecklist(itemID) {
  $.ajax({
    type: "GET",
    url: backendItemUrl,
    data: {
      "function": 'get-item',
      "itemID": itemID,
    },

    success: function(response) {
      var item = JSON.parse(response);
      setItemModalData(item[0]);
      getItemChecklistSidebar(itemID);
      getAllOpenItemChecklists(itemID);
    }
  });
}

function setItemModalData(item) {

  console.log(item);

  // set the title
  $("#item-modal .modal-title").html(item.name);

  // set the modal data-id
  $("#item-modal").attr("data-item-id", item.id);

  // info tab set data
  $("#item-pills-info .info-section.name .content").html(item.name);                // item name
  $("#item-pills-info .info-section.date-due .content").html(item.date_due_date);   // date due
  $("#item-pills-info .info-section.description .content").html(item.description);  // description


  // set the edit item info form values
  $("#edit-item-name").val(item.name);
  $("#edit-item-description").val(item.description);
  setFlatpickrDate($("#edit-item-date-created"), item.date_created);
  setFlatpickrDate($("#edit-item-date-due"), item.date_due);
}

function setFlatpickrDate(element, date) {
  flatpickr(element, {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    altInput: true,
    altFormat: "F j, Y H:i",
    defaultDate: date,
  });
}


function addItemChecklist() {
  var itemID = $("#item-modal").attr('data-item-id');
  var checklistName = $("#new-item-checklist-name").val();

  $.ajax({
    type: "POST",
    url: backendItemUrl,
    data: {
      "function": 'insert-item-checklist',
      "itemID": itemID,
      "checklistName": checklistName,
    },

    success: function(response) {
      setItemChecklistSidebar(JSON.parse(response));

      // clear the input
      $("#new-item-checklist-name").val('');
      $("#new-item-checklist-btn").prop('disabled', true);

      getProjectItems();
    }
  });
}


// populate the item modal checklist sidebar 
function getItemChecklistSidebar(itemID) {
  $.ajax({
    type: "GET",
    url: backendItemUrl,
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

// html for a item checklist sidebar item
function getItemChecklistSidebarHtml(checklist) {
  var html = '';
  html += '<li class="nav-item dropright" data-item-checklist-id="' + checklist.id + '">';
  html += '<a class="nav-link" data-toggle="dropdown" href="#" role="button">' + checklist.name + '</a>';
  html += '<div class="dropdown-menu">';

  if (checklist.open == 'n') {
    html += '<button class="dropdown-item open-item-checklist-btn" type="button" onclick="openItemChecklist(' + checklist.id + ')">Open</button>';
  } else {
    html += '<button class="dropdown-item open-item-checklist-btn" type="button" onclick="openItemChecklist(' + checklist.id + ')" disabled>Open</button>';

  }

  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button">Move up</button>';
  html += '<button class="dropdown-item" type="button">Move down</button>';
  html += '<div class="dropdown-divider"></div>';
  html += '<button class="dropdown-item" type="button" onclick="deleteItemChecklist(' + checklist.id + ')">Delete</button>';
  html += '</div>';
  html += '</li>';

  return html;
}


function getAllOpenItemChecklists(itemID) {
  $.ajax({
    type: "GET",
    url: backendItemUrl,
    data: {
      "function": 'get-open-item-checklists',
      "itemID": itemID,
    },

    success: function(response) {
      var openItemChecklists = JSON.parse(response);
      var size = openItemChecklists.length;

      // display all open checklists
      for (var count = 0; count < size; count++) {
        openItemChecklist(openItemChecklists[count].id);       
      }
    }
  });
}

// open an item checklist
function openItemChecklist(itemChecklistID) {
  // var itemChecklistID = $(itemChecklist).closest(".nav-item").attr("data-item-checklist-id");

  $.ajax({
    type: "GET",
    url: backendItemUrl,
    data: {
      "function": 'get-item-checklist-items',
      "itemChecklistID": itemChecklistID,
    },

    success: function(response) {
      var html = getItemChecklistCardHtml(JSON.parse(response));  // server response
      $("#item-checklists").append(html);                         // add the checklist to the section
      disableItemChecklistSidebarItem(itemChecklistID);           // disable the checklist sidebar open button

    }
  });

}


function getItemChecklistCardHtml(data) {
  var checklistData = data['Item_Checklists'];
  var items = data['Item_Checklist_Items'];

  var html = '';
  html += '<div class="card item-checklist" data-item-checklist-id="' + checklistData.id + '">';
  html += '<div class="card-header">';
  html += '<h6>' + checklistData.name + '</h6>';
  html += '<div class="dropdown"><button class="btn" type="button" data-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded"></i></button><div class="dropdown-menu"><a class="dropdown-item" href="#" onclick="closeItemChecklist(this)">Close</a><a class="dropdown-item" href="#">Rename</a>';
  html += '<a class="dropdown-item" href="#" onclick="deleteItemChecklist(' + checklistData.id + ')">Delete</a>';
  html += '</div></div></div><div class="card-body"><div class="input-group mb-3"><input type="text" class="form-control new-item-checklist-item-input"><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" onclick="addItemChecklistItemFromButton(this)">+</button></div></div><ul class="list-group list-group-flush">';

  // get the html for the checklist items
  for (var count = 0; count < items.length; count++) {
    html += getItemChecklistCardBodyHtml(items[count]);
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
    html += '<input class="form-check-input position-static" type="checkbox" onclick="updateItemChecklistItem(this)">';
    html += '<div class="content">' + itemChecklistItem.content + '</div>';

  // item is incomplete
  } else {
    html += '<input class="form-check-input position-static" type="checkbox" checked onclick="updateItemChecklistItem(this)">';
    html += '<div class="content completed">' + itemChecklistItem.content + '</div>';
  }
  html += '</div>';


  // right portion: dropdown menu
  html += '<div class="right">';


  // dropdown button
  html += '<div class="dropleft"><button class="btn" type="button" data-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>';

  // dropdown menu
  html += '<div class="dropdown-menu"><button class="dropdown-item" type="button" onclick="editItemChecklistItem(this)">Edit</button><div class="dropdown-divider"></div><button class="dropdown-item" type="button">Move up</button><button class="dropdown-item" type="button">Move down</button><div class="dropdown-divider"></div><button class="dropdown-item" type="button" onclick="deleteItemChecklistItem(this)">Delete</button></div>';


  // closing tags
  html += '</div></div></li>';

  return html;
}



function closeItemChecklist(itemChecklist) {
  var itemChecklistID = $(itemChecklist).closest(".item-checklist").attr("data-item-checklist-id");

  $.ajax({
    type: "POST",
    url: backendItemUrl,
    data: {
      "function": 'close-item-checklist',
      "itemChecklistID": itemChecklistID,
    },

    success: function(response) {
      $(itemChecklist).closest(".item-checklist").remove(); // remove the checklist
      enableItemChecklistSidebarItem(itemChecklistID);
    }
  });
}



function getOpenItemModalID() {
  return $("#item-modal").attr("data-item-id");
}


function enableItemChecklistSidebarItem(itemChecklistID) {
  var element = '#item-pills-checklists .sidebar-item-checklists li[data-item-checklist-id="' + itemChecklistID + '"] .open-item-checklist-btn';
  $(element).prop('disabled', false);  // disable the open checklist button
}

function disableItemChecklistSidebarItem(itemChecklistID) {
  var element = '#item-pills-checklists .sidebar-item-checklists li[data-item-checklist-id="' + itemChecklistID + '"] .open-item-checklist-btn';
  $(element).prop('disabled', true);  // disable the open checklist button
}

function deleteItemChecklist(itemChecklistID) {

  if (confirm('Are you sure you want to delete this item checklist?')) {

    $.ajax({
      type: "POST",
      url: backendItemUrl,
      data: {
        "function": 'delete-item-checklist',
        "itemChecklistID": itemChecklistID,
      },

      success: function(response) {

        // remove checklist card
        var checklist = getItemChecklistCard(itemChecklistID);
        $(checklist).remove();

        // reload the item modal
        var itemID = getOpenItemModalID();
        loadItemModalChecklist(itemID);
        getProjectItems();

        // send alert
        toastAlert('Item checklist deleted.');
      }
    });
  }
}

function getItemChecklistCard(itemChecklistID) {
  var element = '#item-checklists .item-checklist[data-item-checklist-id="' + itemChecklistID + '"]';
  return $(element);
}

// update item checklist item
function updateItemChecklistItem(itemChecklistItem) {

  var id = $(itemChecklistItem).closest(".list-group-item").attr("data-item-checklist-item-id");
  var itemChecklist = $(itemChecklistItem).closest(".item-checklist");

  // check if item is completed or not
  if (itemChecklistItem.checked) {
    setItemChecklistItemToComplete(id);
  } else {
    setItemChecklistItemToIncomplete(id);
  }

  $(itemChecklistItem).next().toggleClass('completed');


}

// set the item checklist item to incomplete
function setItemChecklistItemToIncomplete(itemChecklistItemID) {
  $.ajax({
    type: "POST",
    url: backendItemUrl,
    data: {
      "function": 'update-item-checklist-item-incomplete',
      "itemChecklistItemID": itemChecklistItemID
    },
  });
}

// set the item checklist item to complete
function setItemChecklistItemToComplete(itemChecklistItemID) {
  $.ajax({
    type: "POST",
    url: backendItemUrl,
    data: {
      "function": 'update-item-checklist-item-complete',
      "itemChecklistItemID": itemChecklistItemID
    },
  });
}


function addItemChecklistItemFromButton(btn) {
  var itemChecklistID = $(btn).closest(".item-checklist").attr("data-item-checklist-id");
  var content = $(btn).closest(".item-checklist").find(".new-item-checklist-item-input").val();
  var itemChecklist = getItemChecklistCard(itemChecklistID);

  var data = {
    'content': content,
    'function': 'add-item-checklist-item',
    'itemChecklistID': itemChecklistID,
  }

  $.post(backendItemUrl, data, function(response) {
      var newItem = JSON.parse(response);
      appendNewItemChecklistItem(newItem, itemChecklist);

      // clear the input
      $(".new-item-checklist-item-input").val('');
  });
}



function addItemChecklistItem(content, itemChecklistID) {
  var itemChecklist = getItemChecklistCard(itemChecklistID);

  var data = {
    'content': content,
    'function': 'add-item-checklist-item',
    'itemChecklistID': itemChecklistID,
  }

  $.post(backendItemUrl, data, function(response) {
      var newItem = JSON.parse(response);
      appendNewItemChecklistItem(newItem, itemChecklist);

      // clear the input
      $(".new-item-checklist-item-input").val('');
  });
}



function appendNewItemChecklistItem(item, itemChecklist) {
  var html = getItemChecklistCardBodyHtml(item);
  $(itemChecklist).find("ul.list-group").append(html);
}

// adds new item checklist item when enter is hit
$(document).on("keypress", ".new-item-checklist-item-input", function(e) {
  if (e.keyCode == 13) {
    e.preventDefault();
    var content = $(this).val();
    var itemChecklistID = $(this).closest(".item-checklist").attr("data-item-checklist-id");
    addItemChecklistItem(content, itemChecklistID);
  }
});

function deleteItemChecklistItem(item) {

  var itemChecklistItem = $(item).closest('li.list-group-item');      // item checklist item
  var id = $(itemChecklistItem).attr('data-item-checklist-item-id');  // item checklist item id

  // data to pass to the server
  var data = {
    'itemChecklistItemID': id,
    'function': 'delete-item-checklist-item',
  }

  // post the data
  $.post(backendItemUrl, data, function(response) {
    $(itemChecklistItem).remove();  // remove the item
    toastAlert('Item deleted!');
  });
}


function editItemChecklistItem(selector) {
  var item = $(selector).closest('li.list-group-item');
  var contentText = $(item).find('.content').html();
  var id = $(item).attr('data-item-checklist-item-id');

  // add the input html
  var inputHTML = '<input class="form-control edit-item-checklist-item-input" value="' + contentText + '">';
  $(item).append(inputHTML);

  $('.edit-item-checklist-item-input').select();


  // hide the html
  $(item).find('.left').hide();
  $(item).find('.right').hide();

  // user clicks off the input set the value of the input
  $(".edit-item-checklist-item-input").on('focusout', function(e) {

    // get the new content from the input
    var newContent = $(this).val();
    updateItemChecklistItemContent(id, newContent);

    $(this).remove();
    $(item).find('.content').text(newContent);
    $(item).find('.left').show();
    $(item).find('.right').show();
    toastAlert('Item updated!');
  });


  // user clicks enter 
  $(".edit-item-checklist-item-input").on('keypress', function(e) {
    if (e.keyCode == 13) {
      e.preventDefault();

      var newContent = $(this).val();
      updateItemChecklistItemContent(id, newContent);

      $(this).remove();
      $(item).find('.content').text(newContent);
      $(item).find('.left').show();
      $(item).find('.right').show();
      toastAlert('Item updated!');
    }
  });


}


function updateItemChecklistItemContent(itemChecklistItemID, newContent) {
  var data = {
    'itemChecklistItemID': itemChecklistItemID,
    'function': 'update-item-checklist-item-content',
    'content': newContent,
  }

  // send request to the server
  $.post(backendItemUrl, data);
}



function deleteItem() {

  if (confirm('Are you sure you want to delete this item?')) {
    var itemID = getOpenItemModalID();
    var data = {
      'function': 'delete-item',
      'itemID': itemID,
    }

    // send request to the server
    // reload the page after item has been deleted
    $.post(backendItemUrl, data, function(response) {
      location.reload();
    });
  }
}


function updateItemInfo() {

  var itemID = getOpenItemModalID();
  var itemData = getEditItemFormData();

  var data = {
    'itemID': itemID,
    'name': itemData.name,
    'dateDue': itemData.dateDue,
    'dateCreated': itemData.dateCreated,
    'description': itemData.description,
    'function': 'update-item',
  }

  $.post(backendItemUrl, data, function(response) {
    toastAlert('Item updated!');     // send alert
    setItemModalData(JSON.parse(response));
  });

}


function getEditItemFormData() {
  var itemID = getOpenItemModalID();
  var name = $("#edit-item-name").val();
  var dateDue = $("#edit-item-date-due").val();
  var dateCreated = $("#edit-item-date-created").val();
  var description = $("#edit-item-description").val();

  if (dateDue.length <= 0)
    dateDue = null;

  var data =  {
    itemID: itemID,
    name: name,
    dateDue: dateDue,
    dateCreated: dateCreated,
    description: description,
  }

  return data;
}


function addItemNote() {

  var content = $("#new-item-note-input").val();  // note content
  var itemID = getOpenItemModalID();              // item id

  var data = {
    'itemID': itemID,
    'content': content,
    'function': 'insert-item-note',
  }

  $.post(backendItemUrl, data, function(response) {
    getItemNotes(itemID);                                                       // update the item notes
    $("#new-item-note-input").val('');                                          // clear the input
    toastAlert('Note added');                                                   // send success alert
    enableButtonFromInput($("#new-item-note-btn"), $("#new-item-note-input"));  // disable the save button
  });
}


function getItemNotes(itemID) {
  var data = {
    'itemID': itemID,
    'function': 'get-item-notes'
  }

  $.get(backendItemUrl, data, function(response) {
    var itemNotes = JSON.parse(response);
    setItemNotes(itemNotes);
  });

}

function setItemNotes(itemNotes) {

  var size = itemNotes.length;  // number of notes
  var html = '';                // empty html string to place into the notes section

  // generate all the html
  for (var count = 0; count < size; count++)
    html += getItemNoteCardHtml(itemNotes[count]);

  // set the item notes section to the generated html
  $("#item-notes-cards").html(html);
}

function getItemNoteCardHtml(itemNote) {
  var html = '';
  html += '<div class="card card-item-note" data-item-note-id="' + itemNote.id + '">';
  html += '<div class="card-body content">' + itemNote.content + '</div>';
  html += '<div class="card-footer split"><div class="left">';
  html += '<button type="button" class="btn btn-sm btn-secondary edit-item-note-btn" onclick="editItemNote(this)">Edit</button>';
  html += '<button type="button" class="btn btn-sm btn-danger delete-item-note-btn" onclick="deleteItemNote(this)">Delete</button></div>';
  html += '<div class="right"><div class="date">' + itemNote.date_created_display + '</div></div></div></div>';

  return html;
}

function deleteItemNote(btn) {

  if (!confirm('Are you sure you want delete this note?'))
    return;
  
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  var data = {
    'itemNoteID': itemNoteID,
    'function': 'delete-item-note',
  }

  $.post(backendItemUrl, data, function(response) {
    $(itemNote).remove();
    toastAlert('Note removed');
    getProjectItems();
  });
}


// clears the item notes section
function clearItemNotesSection() {
  $("#item-notes-cards").html('');                                            // clear the item note cards
  $("#new-item-note-input").val('');                                          // clear the new note input
  enableButtonFromInput($("#new-item-note-btn"), $("#new-item-note-input"));  // disable the save button
}

function editItemNote(btn) {
  
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");
  var oldContent = $(itemNote).find(".content").html();

  // html for the textarea to edit the note
  var textAreaHtml = '<div class="edit-item-note">';
  textAreaHtml += '<textarea class="form-control edit-item-note-input">' + oldContent + '</textarea>';
  textAreaHtml += '<div class="action-buttons">';
  textAreaHtml += '<button type="button" class="btn btn-sm btn-primary" onclick="updateItemNote(this)">Save</button>';
  textAreaHtml += '<button type="button" class="btn btn-sm btn-danger" onclick="resetItemNote(this)">Cancel</button>';
  textAreaHtml += '</div></div>';
  $(itemNote).find(".content").html(textAreaHtml);

  // hide the footer
  $(itemNote).find(".card-footer").hide();

  // make the body not able to be resized
  $(itemNote).find(".content").addClass("resize-false");

  // disable the edit button
  $(itemNote).find(".edit-item-note-btn").prop('disabled', true);
}


function updateItemNote(btn) {

  var newContent = $(btn).closest(".edit-item-note").find(".edit-item-note-input").val();
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  var data = {
    content: newContent,
    itemNoteID: itemNoteID,
    function: 'update-item-note',
  }

  $.post(backendItemUrl, data, function(response) {
    var updatedItemNote = JSON.parse(response);

    // get the new item card html
    var html = getItemNoteCardHtml(updatedItemNote);

    // replace the old item note card html with the new html
    $(itemNote).replaceWith(html);

    // send confirmation alert
    toastAlert('Note updated');
  });
}

// resets the item note card back to its orginal version
function resetItemNote(btn) {
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  var data = {
    itemNoteID: itemNoteID,
    function: 'get-item-note',
  }

  $.get(backendItemUrl, data, function(response) {
    var oldItemNote = JSON.parse(response);
    var html = getItemNoteCardHtml(oldItemNote);
    $(itemNote).replaceWith(html);
  });
}








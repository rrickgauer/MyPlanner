const queryString = window.location.search;
const URL_PARAMS = new URLSearchParams(queryString);
const PROJECT_ID = URL_PARAMS.get('projectID');
const BACKEND_ITEM_URL = 'project-backend-items.php';
const BACKEND_PROJECT_URL = 'project-backend.php';
const MD_RENDER = window.markdownit();


$(document).ready(function() {
  getChecklists();
  getProjectItems();
  addEventListeners();
});

// adds event listeners to some elements
function addEventListeners() {
  $("#new-item-checklist-btn").on("click", addItemChecklist);
  $("#item-modal").on("hide.bs.modal", closeItemModal);

  // disable a button if an input is empty
  $(".update-button").on("keyup", function () {
    var buttonID = $(this).attr("data-button-id");
    enableButtonFromInput($(buttonID), this);
  });
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

// actions taken when the item modal is closed
function closeItemModal() {
  getProjectItems();                                              // reload the project item cards

  $("#item-modal .modal-body .sidebar-item-checklists").html(''); // clear item checklist sidebar
  $("#item-checklists").html('');                                 // clear the item checklists
  $('#item-modal .modal-title').html('');                         // clear the item modal title
  
  clearItemNotesSection();                                        // clear the item notes section
  collapseAllItemModalSections();                                 // collapse the checklist and notes content
}

// collapses all the item modal sections 
function collapseAllItemModalSections() {
  $("#item-pills-notes .panel").addClass("d-none");       // notes
  $("#item-pills-checklists .panel").addClass("d-none");  // checklists
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


// executes addTodoItem when enter is pressed and the user is focused on the #new-project-checklist-item-input
$(document).on("keypress", "#new-project-checklist-item-input", function (e) {
  if (e.keyCode == 13) {
    e.preventDefault();
    addChecklistItem();
  }
});


// insert a new checklist item
function addChecklistItem() {
  var content = $("#new-project-checklist-item-input").val();
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');

  var data = {
    checklistID: checklistID,
    content: content,
  };

  $.post(BACKEND_PROJECT_URL, data, function(response){
    var checklistItems = JSON.parse(response);      // parse the response
    displayChecklistItems(checklistItems);          // display the checklistItems
    $("#new-project-checklist-item-input").val(''); // clear the input
  });
}


// opens a checklist modal from the seletced option on the sidebar
function openProjectChecklist(checklist) {
  var checklistID = $(checklist).data("id");                            // get the id of the sidebar item selected
  $("#project-checklist-modal").attr('data-checklist-id', checklistID); // add this id to the modal

  getChecklistItems(checklistID);                                       // retrieve the new checklist items

  var listName = $(checklist).html();                                   // get the list name
  $("#project-checklist-modal .modal-title").html(listName);            // set the modal title to the list name

  $('#project-checklist-modal').modal('show');                          // show the checklist
}

// clears the new checklist input in the modal
function clearNewChecklistInput() {
  $("#new-checklist-name").val('');
}


// retrieves the checklist modal from the server
function getChecklistItems(checklistID) {
  var data = {
    checklistID: checklistID,
    data: 'items',
  };

  // send the request to the server
  $.get(BACKEND_PROJECT_URL, data, function(response) {
      displayChecklistItems(JSON.parse(response));
  });
}

// displays the retrieved checklist modal
function displayChecklistItems(data) {
  var size = data.length;
  var html = '';

  // create new table html
  for (var count = 0; count < size; count++) {
    html += getChecklistTableRow(data[count].id, data[count].content, data[count].completed);
  }

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
  var name = $("#new-checklist-name").val();  // get the name of the checklist

  var data = {
    projectID: PROJECT_ID,
    name: name,
  };

  $.post(BACKEND_PROJECT_URL, data, function(response) {
    setChecklistSidebar(JSON.parse(response));
    $('#new-checklist-modal').modal('hide');
    clearNewChecklistInput();
  });
}

// displays the sidebar project checklist data
function setChecklistSidebar(data) {
  const size = data.length;
  var html = '';

  // create html to insert into the sidebar
  for (var count = 0; count < size; count++) {
    html += '<li onclick="openProjectChecklist(this)" data-id="' + data[count].id + '">' + data[count].name;
    html += '<span class="badge badge-secondary">' + data[count].itemCount + '</span>';
    html += '</li>';
  }

  // set the html
  $("#sidebar ul").html(html);
}

// retrieves the project checklists from the server
function getChecklists() {
  var data = {projectID: PROJECT_ID};

  $.get(BACKEND_PROJECT_URL, data, function(response) {
    setChecklistSidebar(JSON.parse(response));
  });
}

// updates the selected project checklist modal title
function setProjectChecklistModalTitle(title) {
  $("#project-checklist-modal .modal-title").html(title);
}

// deletes a checklist
function deleteChecklist() {

  // exit function is user cancels deleteion
  if (!confirm('Are you sure you want to delete?'))
    return;

  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');
  var data = {
    checklistID: checklistID,
    projectID: PROJECT_ID,
    action: 'delete',
  };

  $.post(BACKEND_PROJECT_URL, data, function(response) {
    getChecklists();
    $('#project-checklist-modal').modal('hide');
    toastAlert('Checklist deleted');
  });
  
}

// deletes a checklist item
function deleteChecklistItem(checklistItemID) {
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');

  var data = {
    projectChecklistItemID: checklistItemID,
    checklistID: checklistID,
    action: 'delete',
  };

  $.post(BACKEND_PROJECT_URL, data, function(response) {
    displayChecklistItems(JSON.parse(response));
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

  var data = {
    projectChecklistItemID: checklistItemID,
    checklistID: checklistID,
    action: 'incomplete',
  };

  $.post(BACKEND_PROJECT_URL, data, function(response) {
    displayChecklistItems(JSON.parse(response));
  });
}

// sets a project checklist item to complete
function setChecklistItemComplete(checklistItemID) {
  var checklistID = $("#project-checklist-modal").attr('data-checklist-id');

  var data = {
    projectChecklistItemID: checklistItemID,
    checklistID: checklistID,
    action: 'complete',
  };

  $.post(BACKEND_PROJECT_URL, data, function(response) {
    displayChecklistItems(JSON.parse(response));
  });

}

// deletes a project
// see delete-project.php
function deleteProject() {
  if (confirm('Are you sure you want to delete this project?'))
    window.location.href = 'delete-project.php?projectID=' + PROJECT_ID;
}

// returns the project items
function getProjectItems() {

  var data = {
    function: 'get-items',
    projectID: PROJECT_ID,
  }

  $.get(BACKEND_ITEM_URL, data, function(response) {
    displayProjectItems(JSON.parse(response));
  });
}

// displays a project item
function displayProjectItems(data) {
  var size = data.length;
  var html = '';

  for (var count = 0; count < size; count++) {
    html += getProjectItemCardHTML(data[count]);
  }

  $("#items-deck").html(html);
}

// returns the html for a project item card
function getProjectItemCardHTML(item) {

  var html = '';

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
  html += '<div class="card item-card-stat"><div class="card-body"><div class="left"><i class="bx bx-time"></i></div>';
  html += '<div class="right"><div class="description">Date due</div><div class="data">' + item.date_due_date + '</div></div></div></div>';

  // checklists count
  html += '<div class="card item-card-stat"><div class="card-body"><div class="left"><i class="bx bx-list-check"></i></div>';
  html += '<div class="right"><div class="description">Checklists</div><div class="data">' + item.count_checklists + '</div></div></div></div>';

  // notes count
  html += '<div class="card item-card-stat"><div class="card-body"><div class="left"><i class="bx bx-note"></i></div>';
  html += '<div class="right"><div class="description">Notes</div><div class="data">' + item.count_notes + '</div></div></div></div>';

  // labels
  html += '<div class="card item-card-stat"><div class="card-body">';
  html += '<div class="left"><i class="bx bx-purchase-tag"></i></div>';
  html += '<div class="right"><div class="description">Labels</div><div class="data">' + '</div></div>';
  html += '</div></div></div>';


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

  var data = {
    function: 'insert-item',
    projectID: PROJECT_ID,
    name: itemName,
    description: itemDescription,
  };

  $.post(BACKEND_ITEM_URL, data, function(response) {
    var item = JSON.parse(response);
    setItemModalData(item[0]);
    getItemChecklistSidebar(item[0].id);
    getProjectItems();

    // display the modal
    $('#item-modal').modal('show');
  });
}


// opens an item modal and loads the item's data
function openItemModal(itemID) {
  var data = {
    itemID: itemID,
    function: 'get-item',
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var item = JSON.parse(response);
    setItemModalData(item[0]);
    getItemChecklistSidebar(itemID);
    getAllOpenItemChecklists(itemID);
    getItemNotes(itemID);
    $('#item-modal').modal('show');
  });
}

// loads the item modal checklist data
function loadItemModalChecklist(itemID) {

  var data = {
    function: 'get-item',
    itemID: itemID
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var item = JSON.parse(response);
    setItemModalData(item[0]);
    getItemChecklistSidebar(itemID);
    getAllOpenItemChecklists(itemID);
  });
}

function setItemModalData(item) {
  // set the title
  $("#item-modal .modal-title").html(item.name);

  // set the modal data-id
  $("#item-modal").attr("data-item-id", item.id);

  // info tab set data
  $("#item-pills-info .info-section.name .content").html(item.name); // item name
  $("#item-pills-info .info-section.date-due .content").html(item.date_due_date); // date due
  $("#item-pills-info .info-section.description .content").html(renderMarkdown(item.description)); // description


  // set the edit item info form values
  $("#edit-item-name").val(item.name);
  $("#edit-item-description").val(item.description);
  setFlatpickrDate($("#edit-item-date-created"), item.date_created);
  setFlatpickrDate($("#edit-item-date-due"), item.date_due);

  // determine correct data for complete item button
  if (item.completed == 'n')
    setItemModalCompleteButtonToIncomplete(); // set to incompleted
  else
    setItemModalCompleteButtonToComplete(); // set to completed

}

// sets the flatpickr dates
function setFlatpickrDate(element, date) {
  flatpickr(element, {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    altInput: true,
    altFormat: "F j, Y H:i",
    defaultDate: date,
  });
}

// adds an item checklist to the database
function addItemChecklist() {
  var itemID = $("#item-modal").attr('data-item-id');
  var checklistName = $("#new-item-checklist-name").val();

  var data = {
    itemID: itemID,
    function: 'insert-item-checklist',
    checklistName: checklistName,
  };

  $.post(BACKEND_ITEM_URL, data, function(response) {
    // setItemChecklistSidebar(JSON.parse(response));

    console.log(response);

    // clear the input
    $("#new-item-checklist-name").val('');
    $("#new-item-checklist-btn").prop('disabled', true);

    getProjectItems();
  });
}


// populate the item modal checklist sidebar 
function getItemChecklistSidebar(itemID) {

  var data = {
    function: 'get-item-checklists',
    itemID: itemID,
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    setItemChecklistSidebar(JSON.parse(response));
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
  }
  else {
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

// opens all the item checklists that are currently save
function getAllOpenItemChecklists(itemID) {

  var data = {
    function: 'get-open-item-checklists',
    itemID: itemID,
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var openItemChecklists = JSON.parse(response);
    var size = openItemChecklists.length;

    // display all open checklists
    for (var count = 0; count < size; count++) {
      openItemChecklist(openItemChecklists[count].id);
    }
  });
}

// open an item checklist
function openItemChecklist(itemChecklistID) {

  var data = {
    function: 'get-item-checklist-items',
    itemChecklistID: itemChecklistID,
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var html = getItemChecklistCardHtml(JSON.parse(response));  // server response
    $("#item-checklists").append(html);                         // add the checklist to the section
    disableItemChecklistSidebarItem(itemChecklistID);           // disable the checklist sidebar open button
  });
}

// returns the html of an item checklist card
function getItemChecklistCardHtml(data) {
  var checklistData = data.Item_Checklists; // item checklist data
  var items = data.Item_Checklist_Items;    // item checklist items

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

  html += '</ul></div></div>';

  return html;
}

// generates and returns the html for the card body of an item checklist card
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
  }
  else {
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

// closes an item checklist
function closeItemChecklist(itemChecklist) {
  var itemChecklistID = $(itemChecklist).closest(".item-checklist").attr("data-item-checklist-id"); // id if the parent item checklist

  // data to be sent to the server
  var data = {
    function: 'close-item-checklist',
    itemChecklistID: itemChecklistID,
  };

  $.post(BACKEND_ITEM_URL, data, function(response) {
    $(itemChecklist).closest(".item-checklist").remove(); // remove the checklist
    enableItemChecklistSidebarItem(itemChecklistID);
  });
}

// returns the id of the item modal that is currently open
function getOpenItemModalID() {
  return $("#item-modal").attr("data-item-id");
}

// allows the specified item checklist sidebar item to be opened
function enableItemChecklistSidebarItem(itemChecklistID) {
  var element = '#item-pills-checklists .sidebar-item-checklists li[data-item-checklist-id="' + itemChecklistID + '"] .open-item-checklist-btn';
  $(element).prop('disabled', false); // disable the open checklist button
}

// disallows the specified item checklist sidebar item to be opened
function disableItemChecklistSidebarItem(itemChecklistID) {
  var element = '#item-pills-checklists .sidebar-item-checklists li[data-item-checklist-id="' + itemChecklistID + '"] .open-item-checklist-btn';
  $(element).prop('disabled', true); // disable the open checklist button

}

// deletes an item checklist
function deleteItemChecklist(itemChecklistID) {

  // exit function if user elects to not delete the item checklist
  if (!confirm('Are you sure you want to delete this item checklist?'))
    return;

  var data = {
    function: 'delete-item-checklist',
    itemChecklistID: itemChecklistID,
  };

  $.post(BACKEND_ITEM_URL, data, function(response) {
    // remove checklist card
    var checklist = getItemChecklistCard(itemChecklistID);
    $(checklist).remove();

    // reload the item modal
    var itemID = getOpenItemModalID();
    loadItemModalChecklist(itemID);
    getProjectItems();

    // send confirmation alert
    toastAlert('Item checklist deleted.');
  });  
}

function getItemChecklistCard(itemChecklistID) {
  var element = '#item-checklists .item-checklist[data-item-checklist-id="' + itemChecklistID + '"]';
  return $(element);
}

// update item checklist item
function updateItemChecklistItem(itemChecklistItem) {

  var id = $(itemChecklistItem).closest(".list-group-item").attr("data-item-checklist-item-id");

  // check if item is completed or not
  if (itemChecklistItem.checked) {
    setItemChecklistItemToComplete(id);
  }
  else {
    setItemChecklistItemToIncomplete(id);
  }

  $(itemChecklistItem).next().toggleClass('completed'); // toggle the class to completed
}

// set the item checklist item to incomplete
function setItemChecklistItemToIncomplete(itemChecklistItemID) {
  var data = {
    function: 'update-item-checklist-item-incomplete',
    itemChecklistItemID: itemChecklistItemID,
  };

  $.post(BACKEND_ITEM_URL, data);
}

// set the item checklist item to complete
function setItemChecklistItemToComplete(itemChecklistItemID) {
  var data = {
    function: 'update-item-checklist-item-complete',
    itemChecklistItemID: itemChecklistItemID,
  };

  $.post(BACKEND_ITEM_URL, data);
}

// adds and item checklist item when the add button is clicked
function addItemChecklistItemFromButton(btn) {
  var itemChecklistID = $(btn).closest(".item-checklist").attr("data-item-checklist-id");
  var content = $(btn).closest(".item-checklist").find(".new-item-checklist-item-input").val();
  addItemChecklistItem(content, itemChecklistID);
}

// adds and item checklist to the database
function addItemChecklistItem(content, itemChecklistID) {
  var itemChecklist = getItemChecklistCard(itemChecklistID);

  var data = {
    content: content,
    function: 'add-item-checklist-item',
    itemChecklistID: itemChecklistID,
  };

  $.post(BACKEND_ITEM_URL, data, function (response) {
    var newItem = JSON.parse(response);
    appendNewItemChecklistItem(newItem, itemChecklist);
    $(".new-item-checklist-item-input").val('');  // clear the input
  });
}

// appends an item to the item checklist
function appendNewItemChecklistItem(item, itemChecklist) {
  var html = getItemChecklistCardBodyHtml(item);
  $(itemChecklist).find("ul.list-group").append(html);
}

// adds new item checklist item when enter is hit
$(document).on("keypress", ".new-item-checklist-item-input", function (e) {
  if (e.keyCode == 13) {
    e.preventDefault();
    var content = $(this).val();
    var itemChecklistID = $(this).closest(".item-checklist").attr("data-item-checklist-id");
    addItemChecklistItem(content, itemChecklistID);
  }
});

// delete an item checklist item
function deleteItemChecklistItem(item) {
  var itemChecklistItem = $(item).closest('li.list-group-item');      // item checklist item
  var id = $(itemChecklistItem).attr('data-item-checklist-item-id');  // item checklist item id

  // data to pass to the server
  var data = {
    itemChecklistItemID: id,
    function: 'delete-item-checklist-item',
  };

  // post the data
  $.post(BACKEND_ITEM_URL, data, function (response) {
    $(itemChecklistItem).remove(); // remove the item
    toastAlert('Item deleted!');
  });
}


function editItemChecklistItem(selector) {
  var item = $(selector).closest('li.list-group-item');   // item elements
  var contentText = $(item).find('.content').html();      // the content text
  var id = $(item).attr('data-item-checklist-item-id');   // id of the item checklist item

  // add the input html
  var inputHTML = '<input class="form-control edit-item-checklist-item-input" value="' + contentText + '">';
  $(item).append(inputHTML);

  $('.edit-item-checklist-item-input').select();

  // hide the html
  $(item).find('.left').hide();
  $(item).find('.right').hide();

  // user clicks off the input set the value of the input
  $(".edit-item-checklist-item-input").on('focusout', function (e) {

    // get the new content from the input
    var newContent = $(this).val();
    updateItemChecklistItemContent(id, newContent);

    $(this).remove();
    $(item).find('.content').text(newContent);
    $(item).find('.left').show();
    $(item).find('.right').show();
    toastAlert('Item updated!');
  });


  // user clicks enter to update an item checklist item
  $(".edit-item-checklist-item-input").on('keypress', function (e) {
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

// updates the item checklist item content
function updateItemChecklistItemContent(itemChecklistItemID, newContent) {
  var data = {
    'itemChecklistItemID': itemChecklistItemID,
    'function': 'update-item-checklist-item-content',
    'content': newContent,
  };

  // send request to the server
  $.post(BACKEND_ITEM_URL, data);
}

// deletes an item
function deleteItem() {
  // exit function if user does not confirm deletion
  if (!confirm('Are you sure you want to delete this item?'))
    return;

  var itemID = getOpenItemModalID();
  var data = {
    function: 'delete-item',
    itemID: itemID,
  };

  // send request to the server
  // reload the page after item has been deleted
  $.post(BACKEND_ITEM_URL, data, function (response) {
    location.reload();
  });
}

// updates an item's info
function updateItemInfo() {
  var itemID = getOpenItemModalID();
  var itemData = getEditItemFormData();

  var data = {
    itemID     : itemID,
    name       : itemData.name,
    dateDue    : itemData.dateDue,
    dateCreated: itemData.dateCreated,
    description: itemData.description,
    function   : 'update-item',
  };

  $.post(BACKEND_ITEM_URL, data, function (response) {
    toastAlert('Item updated!'); // send alert
    setItemModalData(JSON.parse(response));
  });

}

// returns the edit item form data
function getEditItemFormData() {
  var itemID      = getOpenItemModalID();
  var name        = $("#edit-item-name").val();
  var dateDue     = $("#edit-item-date-due").val();
  var dateCreated = $("#edit-item-date-created").val();
  var description = $("#edit-item-description").val();

  if (dateDue.length <= 0)
    dateDue = null;

  var data = {
    itemID     : itemID,
    name       : name,
    dateDue    : dateDue,
    dateCreated: dateCreated,
    description: description,
  };

  return data;
}

// adds an item note to the database
function addItemNote() {
  var content = $("#new-item-note-input").val(); // note content
  var itemID = getOpenItemModalID(); // item id

  var data = {
    itemID: itemID,
    content: content,
    function: 'insert-item-note',
  };

  $.post(BACKEND_ITEM_URL, data, function (response) {
    getItemNotes(itemID);                                                       // update the item notes
    $("#new-item-note-input").val('');                                          // clear the input
    toastAlert('Note added');                                                   // send success alert
    enableButtonFromInput($("#new-item-note-btn"), $("#new-item-note-input"));  // disable the save button
  });
}

// retrieves the an item's notes
function getItemNotes(itemID) {
  var data = {
    itemID: itemID,
    function: 'get-item-notes'
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var itemNotes = JSON.parse(response);
    setItemNotes(itemNotes);
  });
}

// sets the item notes
function setItemNotes(itemNotes) {
  var size = itemNotes.length;  // number of notes
  var html = '';                // empty html string to place into the notes section

  // generate all the html
  for (var count = 0; count < size; count++)
    html += getItemNoteCardHtml(itemNotes[count]);

  // set the item notes section to the generated html
  $("#item-notes-cards").html(html);
}

// generates and returns the html for an item note
function getItemNoteCardHtml(itemNote) {
  var content = renderMarkdown(itemNote.content);

  var html = '';
  html += '<div class="card card-item-note" data-item-note-id="' + itemNote.id + '">';
  html += '<div class="card-body content github-css">' + content + '</div>';
  html += '<div class="card-footer split"><div class="left">';
  html += '<button type="button" class="btn btn-sm btn-secondary edit-item-note-btn" onclick="editItemNote(this)">Edit</button>';
  html += '<button type="button" class="btn btn-sm btn-danger delete-item-note-btn" onclick="deleteItemNote(this)">Delete</button></div>';
  html += '<div class="right"><div class="date">' + itemNote.date_created_display + '</div></div></div></div>';

  return html;
}

// deletes an item note
function deleteItemNote(btn) {

  // exit function if user does not confirm deletion
  if (!confirm('Are you sure you want delete this note?'))
    return;

  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  var data = {
    'itemNoteID': itemNoteID,
    'function': 'delete-item-note',
  };

  $.post(BACKEND_ITEM_URL, data, function (response) {
    $(itemNote).remove();       // remove the item note from the dom
    toastAlert('Note removed'); // send alert
    getProjectItems();          // updates the project items in the background
  });
}


// clears the item notes section
function clearItemNotesSection() {
  $("#item-notes-cards").html(''); // clear the item note cards
  $("#new-item-note-input").val(''); // clear the new note input
  enableButtonFromInput($("#new-item-note-btn"), $("#new-item-note-input")); // disable the save button
}

// edit an item note
function editItemNote(btn) {
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  // get the old content from the server
  var data = {
    itemNoteID: itemNoteID,
    function: 'get-item-note',
  };

  $.get(BACKEND_ITEM_URL, data, function(response) {
    var oldContent = JSON.parse(response).content;

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
  });

}

// updates an item note
function updateItemNote(btn) {
  var newContent = $(btn).closest(".edit-item-note").find(".edit-item-note-input").val();
  var itemNote = $(btn).closest(".card-item-note");
  var itemNoteID = $(itemNote).attr("data-item-note-id");

  var data = {
    content: newContent,
    itemNoteID: itemNoteID,
    function: 'update-item-note',
  };

  $.post(BACKEND_ITEM_URL, data, function (response) {
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
  };

  $.get(BACKEND_ITEM_URL, data, function (response) {
    var oldItemNote = JSON.parse(response);
    var html = getItemNoteCardHtml(oldItemNote);
    $(itemNote).replaceWith(html);
  });
}

// sets an item to completed
function updateItemCompleted() {
  var itemID = getOpenItemModalID();

  // item is incomplete
  if ($("#item-modal").attr("data-item-completed") == 'n') {
    setItemToComplete(itemID);
    toastAlert('Item completed');
  }

  // item is complete
  else {
    setItemToIncomplete(itemID);
    toastAlert('Item incompleted');
  }

  getProjectItems();  // update the item cards in the background
}

// sets an item to complete
function setItemToComplete(itemID) {
  var data = {
    itemID: itemID,
    function: 'update-item-to-complete',
  };

  $.post(BACKEND_ITEM_URL, data, setItemModalCompleteButtonToComplete);
}

// sets an item to incomplete
function setItemToIncomplete(itemID) {
  var data = {
    itemID: itemID,
    function: 'update-item-to-incomplete',
  };

  $.post(BACKEND_ITEM_URL, data, setItemModalCompleteButtonToIncomplete);
}

// sets the complete item modal button to complete
function setItemModalCompleteButtonToComplete() {
  var html = '<i class="bx bx-check-circle"></i>&nbsp;Mark incomplete';
  $("#complete-item-modal-button").addClass('completed').removeClass('incompleted').html(html);
  $("#item-modal").attr("data-item-completed", 'y');
}

// sets the complete item modal button to incomplete
function setItemModalCompleteButtonToIncomplete() {
  var html = '<i class="bx bx-check-circle"></i>&nbsp;Mark complete';
  $("#complete-item-modal-button").addClass('incompleted').removeClass('completed').html(html);
  $("#item-modal").attr("data-item-completed", 'n');
}

// hide a section of the item modal from show details button
$(document).ready(function () {
  $(".btn-show-details").on("click", function () {
    $(this).closest(".info-section").find(".panel").toggleClass('d-none');
  });
});

// returns rendered markdown
function renderMarkdown(input) {
  return MD_RENDER.render(input);
}
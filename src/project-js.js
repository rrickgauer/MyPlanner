const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const projectID = urlParams.get('projectID');

$(document).ready(function() {
getChecklists();
});


function addChecklistItem() {
var content = $("#new-project-checklist-item-input").val();
var checklistID = $("#project-checklist-modal").data("checklist-id");

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
    getChecklistItems(checklistID); 

    // clear the input
    $("#new-project-checklist-item-input").val('');

  },
});
}


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

function displayChecklistItems(data) {
const size = data.length;
var html = '';

// create new table html
for (var count = 0; count < size; count++) 
  html += getChecklistTableRow(data[count].id, data[count].content);

// set the table to the new html
$("#project-checklist-modal-items tbody").html(html);

}


function getChecklistTableRow(id, content) {
var tr = '';
tr += '<tr data-project-checklist-item-id="' + id + '">';
tr += '<td><input type="checkbox"></td>';
tr += '<td>' + content + '</td>';
tr += '<td><i class="bx bx-trash"></i></td>';
tr += '</tr>';

return tr;
}

function activateSidebar() {
$('#sidebar').toggleClass('active');
}


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


function setChecklistSidebar(data) {
const size = data.length;
var html = '';

// create html to insert into the sidebar
for (var count = 0; count < size; count++) 
  html += '<li onclick="openProjectChecklist(this)" data-id="' + data[count].id + '">' + data[count].name + '</a></li>';

$("#sidebar ul").html(html);
}

function clearNewChecklistInput() {
$("#new-checklist-name").val('');
}

function getChecklists() {


$.ajax({
  type: "GET",
  url: 'project-backend.php',
  data: {
    "projectID": projectID
  },

  success: function(response) {
    var data = JSON.parse(response);
    setChecklistSidebar(data);
  }
});
}


function openProjectChecklist(checklist) {
var checklistID = $(checklist).data("id");
$("#project-checklist-modal").attr('data-checklist-id', checklistID);
getChecklistItems(checklistID);

// set the modal title to the list name
var listName = $(checklist).html();
$("#project-checklist-modal .modal-title").html(listName);

// show the checklist
$('#project-checklist-modal').modal('show');
}

function setProjectChecklist(data) {

}

function setProjectChecklistModalTitle(title) {
$("#project-checklist-modal .modal-title").html(title);
}

function deleteChecklist() {

var checklistID = $("#project-checklist-modal").attr('data-checklist-id');
// alert(checklistID);

$.ajax({
  type: "POST",
  url: 'project-backend.php',
  data: {
    "checklistID": checklistID,
    "projectID": projectID,
    "action": 'delete',
  },

  success: function(response) {
    // var data = JSON.parse(response);
    // console.log(response);

    getChecklists();
    $('#project-checklist-modal').modal('hide');
  }
});




}
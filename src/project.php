<?php include('functions.php'); ?>
<?php include('session.php'); ?>
<?php $projectInfo = getProjectInfo($_GET['projectID'])->fetch(PDO::FETCH_ASSOC); ?>


<!DOCTYPE html>
<html>
<head>
  <?php include('header.php'); ?>
  <title><?php echo $projectInfo['name']; ?></title>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="wrapper">

    <!-- put project checklists here -->
    <nav id="sidebar">

      <div class="sidebar-header">
        <h3>Checklists</h3>
        <i class='bx bx-plus-circle pointer' data-toggle="modal" data-target="#new-checklist-modal"></i>
      </div>
      
      <!-- checklists -->
      <ul>
        
      </ul>
    </nav>

    <div id="content">
      <button type="button" class="btn btn-primary" onclick="activateSidebar()">View checklists</button>

      <div class="project-info">

        <!-- name -->
        <h1><?php echo $projectInfo['name']; ?></h1>

        <div class="row">

          <!-- description -->  
          <div class="col-sm-12 col-md-4">
            <div class="project-info-element">
              <p class="descriptor">Description</p>
              <p class="data"><?php echo $projectInfo['description']; ?></p>          
            </div>
          </div>

          <!-- date created and due -->
          <div class="col-sm-12 col-md-4">
            <!-- date created -->
            <div class="project-info-element">
              <p class="descriptor">Created</p>
              <p class="data"><?php echo $projectInfo['date_created'] . ' ' . $projectInfo['date_created_time']; ?></p>
            </div>

            <!-- date due -->
            <div class="project-info-element">
              <p class="descriptor">Date due</p>
              <p class="data"><?php echo $projectInfo['date_due'] . ' ' . $projectInfo['date_due_time']; ?></p>
            </div>
          </div>

          <!-- notes -->
          <div class="col-sm-12 col-md-4">
            <div class="project-info-element">
              <p class="descriptor">Notes</p>
              <p class="data"></p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  <div class="modals">
    
    <!-- new checklist -->
    <div class="modal" id="new-checklist-modal" data-backdrop="static" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">New checklist</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearNewChecklistInput()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form method="post">
              <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" id="new-checklist-name" name="new-checklist-name">
              </div>
              <button type="button" class="btn btn-primary float-right" onclick="createChecklist()">Create checklist</button>
            </form>
            
          </div>
        </div>
      </div>
    </div>

    <!-- Project checklist -->
    <div class="modal" id="project-checklist-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">MODAL TITLE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearNewChecklistInput()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            
            <!-- add item input group -->
            <div class="input-group">
              <input type="text" id="new-project-checklist-item-input" class="form-control" placeholder="Checklist item">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" onclick="addChecklistItem()"><i class='bx bx-plus'></i></button>
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                  <button class="dropdown-item">Rename</button>
                  <button class="dropdown-item" onclick="deleteChecklist()">Delete</button>
                </div>
              </div>
            </div>

            <br>


            <!-- checklist items -->
            <table class="table" id="project-checklist-modal-items">
              <tbody>
              </tbody>
            </table>


          </div>
        </div>
      </div>
    </div>


  </div>


  
  <?php include('footer.php'); ?>

  <script>
    
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



  </script>

</body>
</html>
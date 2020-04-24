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
        <i class='bx bx-plus-circle' data-toggle="modal" data-target="#new-checklist-modal"></i>
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


  </div>


  
  <?php include('footer.php'); ?>

  <script>
    
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    $(document).ready(function() {
      getChecklists();
    });

    function activateSidebar() {
      $('#sidebar').toggleClass('active');
    }


    function createChecklist() {

      // get the name of the checklist
      var name = $("#new-checklist-name").val();
      const projectID = urlParams.get('projectID');

      $.ajax({
        type: "POST",
        url: 'insert-checklist.php',
        data: {
          "projectID": projectID,
          "name": name
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
        html += '<li><a href="#">' + data[count].name + '</a></li>';
      
      $("#sidebar ul").html(html);
    }

    function clearNewChecklistInput() {
      $("#new-checklist-name").val('');
    }

    function getChecklists() {
      const projectID = urlParams.get('projectID');

      $.ajax({
        type: "GET",
        url: 'get-checklist.php',
        data: {
          "projectID": projectID
        },

        success: function(response) {
          var data = JSON.parse(response);
          setChecklistSidebar(data);
        }
      });
    }


  </script>

</body>
</html>
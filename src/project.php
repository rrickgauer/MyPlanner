<?php 
include('functions.php'); 
include('session.php'); 

// update the project name
if (isset($_POST['new-project-name'])) 
  updateProjectName($_GET['projectID'], $_POST['new-project-name']);

// load the project info
$projectInfo = getProjectInfo($_GET['projectID'])->fetch(PDO::FETCH_ASSOC); 

?>


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
  
      <div class="project-buttons">
        
        <!-- checklist sidebar -->
        <button type="button" class="btn btn-primary" onclick="activateSidebar()"><i class='bx bx-menu'></i></button>

        <!-- project settings -->
        <div class="dropleft">
          <button class="btn btn-primary" type="button" data-toggle="dropdown"><i class='bx bx-cog'></i></button>
          <div class="dropdown-menu">
            <button class="dropdown-item" type="button" data-toggle="modal" data-target="#rename-project-modal">Rename</button>
            <button class="dropdown-item" type="button" onclick="deleteProject()">Delete</button>
          </div>
        </div>

      </div>


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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

                <!-- project checklist dropdown menu items -->
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

    <!-- rename project -->
    <div class="modal fade" id="rename-project-modal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Rename project</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form method="post">
              <!-- new project name input -->
              <div class="form-group">
                <label for="project-name">New project name:</label>
                <input type="text" class="form-control" name="new-project-name" id="new-project-name" maxlength="50">
              </div>

              <!-- submit button -->
              <input type="submit" value="Save" class="btn btn-primary float-right">
            </form>

          </div>
        </div>
      </div>
    </div>


  </div>


  
  <?php include('footer.php'); ?>

  <script src="project-js.js"></script>

</body>
</html>
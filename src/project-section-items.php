<h5>Items</h5>

<!-- new item button dropdown -->
<div class="dropright">
  <!-- new item button -->
  <button class="btn btn-secondary" type="button" data-toggle="dropdown">New item</button>
  
  <!-- menu -->
  <div class="dropdown-menu dropdown-form">

    <!-- name -->
    <div class="form-group">
      <label for="new-item-name">Name:</label>
      <input type="text" id="new-item-name" class="form-control">
    </div>
    
    <!-- description -->
    <div class="form-group">
      <label for="new-item-description">Description:</label>
      <textarea class="form-control" rows="5" id="new-item-description" name="description"></textarea>
    </div>
    
    <!-- create item submit button -->
    <button class="btn btn-primary float-right" type="button" onclick="newProjectItem()">Create</button>
  </div>
</div>

<br>

<!-- item cards go here -->
<div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-4" id="items-deck"></div>


<!-- item modal -->
<div class="modal" id="item-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <!-- header -->
      <div class="modal-header">
        <h5 class="modal-title">Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <!-- body -->
      <div class="modal-body">
        
        <!-- item modal navbar -->
        <ul class="nav nav-pills justify-content-center" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="item-pills-checklists-tab" data-toggle="pill" href="#item-pills-checklists" role="tab">Checklists</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="item-pills-notes-tab" data-toggle="pill" href="#item-pills-notes" role="tab">Notes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="item-pills-info-tab" data-toggle="pill" href="#item-pills-info" role="tab">Info</a>
          </li>
        </ul>


        <!-- tab content -->
        <div class="tab-content">

          <!-- checklists -->
          <div class="tab-pane fade show active" id="item-pills-checklists" role="tabpanel">

            <br>
            <h1 class="custom-font">Checklists</h1><br>
            <div class="row">
              <div class="col-2">

                <b>Current lists</b><br>
                <!-- side bar of item checklists -->
                <ul class="nav nav-pills flex-column sidebar-item-checklists">
                </ul>
                
                <!-- new project checklist dropdown -->
                <div class="dropright">
                  <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown">Add checklist</button>
                  <div class="dropdown-menu">
                    <form>
                      <div class="form-group">
                        <label for="new-project-checklist-name">Name:</label>
                        <input type="text" id="new-project-checklist-name" class="form-control">
                      </div>
                      <button id="new-project-checklist-btn" type="button" class="btn btn-primary float-right" disabled>Add</button>
                    </form>
                  </div>
                </div>

              </div>


              <div class="col-10" id="item-checklists">

                <div class="card item-checklist">
                  <div class="card-header">
                    <h6>Checklist name</h6>
                    
                    <!-- checklist dropdown -->
                    <div class="dropdown">
                      <button class="btn" type="button" data-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded'></i></button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Close</a>
                        <a class="dropdown-item" href="#">Rename</a>
                        <a class="dropdown-item" href="#">Mark all incomplete</a>
                      </div>
                    </div>

                  </div>

                  <div class="card-body">
                    <!-- new item input -->
                    <div class="input-group mb-3">
                      <input type="text" class="form-control">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">+</button>
                      </div>
                    </div>
                    
                    <!-- checklist items -->
                    <ul class="list-group list-group-flush">

                      <li class="list-group-item">
                        <div class="left">
                          <i class='bx bx-checkbox checkbox'></i>
                          <div class="content">content</div>
                        </div>
                        <div class="right">
                          <div class="trash float-right"><i class='bx bx-trash' ></i></div>
                        </div>
                      </li>

                      <li class="list-group-item">
                        <div class="left">
                          <i class='bx bx-checkbox-checked' ></i>
                          <div class="content">content</div>
                        </div>
                        <div class="right">
                          <div class="trash float-right"><i class='bx bx-trash' ></i></div>
                        </div>
                      </li>

                      <li class="list-group-item">
                        <div class="left">
                          <i class='bx bx-checkbox' ></i>
                          <div class="content">content</div>
                        </div>
                        <div class="right">
                          <i class='bx bx-trash trash' ></i>
                        </div>
                      </li>








                    </ul>
                  </div>
                </div>

              </div>
            </div>
          </div>


          <!-- notes -->
          <div class="tab-pane fade" id="item-pills-notes" role="tabpanel">
            profile
          </div>

          
          <!-- info -->
          <div class="tab-pane fade" id="item-pills-info" role="tabpanel">
            info part
          </div>

        </div>

      </div>
      

    </div>
  </div>
</div>
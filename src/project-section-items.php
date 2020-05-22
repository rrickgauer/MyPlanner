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
      <input type="text" id="new-item-name" class="form-control update-button" data-button-id="#new-item-btn">
    </div>
    
    <!-- description -->
    <div class="form-group">
      <label for="new-item-description">Description:</label>
      <textarea class="form-control" rows="5" id="new-item-description" name="description"></textarea>
    </div>
    
    <!-- create item submit button -->
    <button class="btn btn-primary float-right" id="new-item-btn" type="button" onclick="newProjectItem()" disabled>Create</button>
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
            <a class="nav-link" id="item-pills-checklists-tab" data-toggle="pill" href="#item-pills-checklists" role="tab">Checklists</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="item-pills-notes-tab" data-toggle="pill" href="#item-pills-notes" role="tab">Notes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="item-pills-info-tab" data-toggle="pill" href="#item-pills-info" role="tab">Info</a>
          </li>
        </ul>


        <!-- tab content -->
        <div class="tab-content">

          <!-- checklists -->
          <div class="tab-pane fade" id="item-pills-checklists" role="tabpanel">

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
                        <input type="text" id="new-item-checklist-name" class="form-control update-button" data-button-id="#new-item-checklist-btn">
                      </div>
                      <button id="new-item-checklist-btn" type="button" class="btn btn-primary float-right" disabled>Add</button>
                    </form>
                  </div>
                </div>

              </div>

              <!-- open checklists go here -->
              <div class="col-10" id="item-checklists">
              </div>
            </div>
          </div>


          <!-- notes -->
          <div class="tab-pane fade show active" id="item-pills-notes" role="tabpanel">
            <h1 class="custom-font">Notes</h1>

            <!-- new note input -->
            <form>
              <div class="form-group">
                <label for="new-item-note-input">New note:</label>
                <textarea id="new-item-note-input" class="form-control update-button" data-button-id="#new-item-note-btn" rows="1" placeholder="Write a note..."></textarea>
              </div>

              <button type="button" class="btn btn-primary" id="new-item-note-btn" onclick="addItemNote()" disabled>Save</button>
            </form>

            


            <!-- item note cards -->
            <div id="item-notes-cards"></div>






            

          </div>

          
          <!-- info -->
          <div class="tab-pane fade" id="item-pills-info" role="tabpanel">

            <div class="row">

              <div class="col-9">

              <div class="info-section name">
                <div class="heading">
                  <div class="icon"><i class='bx bx-card'></i></div>
                  <div class="description">Item name</div>
                </div>
                <div class="content"></div>
              </div>

              <div class="info-section date-due">
                <div class="heading">
                  <div class="icon"><i class='bx bx-time'></i></div>
                  <div class="description">Date due</div>
                </div>
                <div class="content"></div>
              </div>

              <div class="info-section description">
                <div class="heading">
                  <div class="icon"><i class='bx bx-detail'></i></div>
                  <div class="description">Description</div>
                </div>
                <div class="content"></div>
              </div>

              </div>

              <div class="col-3">
                <h6>Actions</h6>

                <div class="list-group list-group-flush">
                  
                  <div class="dropleft">
                    <button type="button" class="list-group-item list-group-item-action" data-toggle="dropdown">
                      <i class='bx bx-pencil'></i>&nbsp;Edit
                    </button>

                    <div class="dropdown-menu dropdown-form" id="edit-item-info-menu">
                      <h5>Edit item info</h5>
                      
                      <!-- name -->
                      <div class="form-group">
                        <label for="edit-item-name">Name:</label>
                        <input type="text" id="edit-item-name" class="form-control">
                      </div>
                      
                      <!-- date due -->
                      <div class="form-group">
                        <label for="edit-item-date-due">Date due:</label>
                        <input type="text" id="edit-item-date-due" class="form-control">
                      </div>

                      <!-- date created -->
                      <div class="form-group">
                        <label for="edit-item-date-created">Date created:</label>
                        <input type="text" id="edit-item-date-created" class="form-control">
                      </div>

                      <!-- description -->
                      <div class="form-group">
                        <label for="edit-item-description">Description:</label>
                        <textarea id="edit-item-description" class="form-control" rows="5"></textarea>
                      </div>

                      <button type="button" class="btn btn-primary float-right" onclick="updateItemInfo()">Save</button>





  


                    </div>

                  </div>

                  

                  <button type="button" class="list-group-item list-group-item-action" onclick="deleteItem()">
                    <i class='bx bx-trash'></i>&nbsp;Delete
                  </button>


                </div>
              </div>






            </div>

          </div>

        </div>

      </div>
      

    </div>
  </div>
</div>
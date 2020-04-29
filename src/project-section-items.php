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



<!-- Modal -->
<div class="modal" id="item-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">


      <div class="modal-header">
        <h5 class="modal-title">Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">

          <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab">Checklists</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab">Notes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-info-tab" data-toggle="pill" href="#pills-info" role="tab">Info</a>
          </li>
        </ul>



        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel">

            <br>

            <h1 class="custom-font">Checklists</h1><br>
            
            <div class="row">

              <div class="col-2">

                <b>Current lists</b><br>

                <ul class="nav nav-pills flex-column">

                  <li class="nav-item dropright">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button">List name</a>
                    <div class="dropdown-menu">
                      <button class="dropdown-item" type="button">Open</button>
                      <button class="dropdown-item" type="button">Close</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Move up</button>
                      <button class="dropdown-item" type="button">Move down</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Delete</button>
                    </div>
                  </li>

                  <li class="nav-item dropright">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button">List name</a>
                    <div class="dropdown-menu">
                      <button class="dropdown-item" type="button">Open</button>
                      <button class="dropdown-item" type="button">Close</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Move up</button>
                      <button class="dropdown-item" type="button">Move down</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Delete</button>
                    </div>
                  </li>

                  <li class="nav-item dropright">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button">List name</a>
                    <div class="dropdown-menu">
                      <button class="dropdown-item" type="button">Open</button>
                      <button class="dropdown-item" type="button">Close</button>
                      <button class="dropdown-item" type="button">Rename</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Move up</button>
                      <button class="dropdown-item" type="button">Move down</button>
                      <div class="dropdown-divider"></div>
                      <button class="dropdown-item" type="button">Delete</button>
                    </div>
                  </li>
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


          <div class="tab-pane fade" id="pills-profile" role="tabpanel">
            profile
          </div>


          <div class="tab-pane fade" id="pills-info" role="tabpanel">
            info part
          </div>

        </div>



        









      </div>
      




    </div>
  </div>
</div>
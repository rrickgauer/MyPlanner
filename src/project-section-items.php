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
        <h5 class="modal-title" id="exampleModalLabel">Item</h5>
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
        </ul>
  
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
    
            <br>
            
            <div class="row">

              <div class="col-3">

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

 
              </div>
              
            </div>






            







          </div>































          <div class="tab-pane fade" id="pills-profile" role="tabpanel">
            profile
          </div>

        </div>



        









      </div>
      




    </div>
  </div>
</div>
<h2 class="custom-font">Items</h2>

<div class="item-toolbar">
  <div class="buttons">

    <!-- new item button dropdown menu -->
    <div class="dropdown mr-2">
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

    <!-- item sorting dropdown -->
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
        Sort items
      </button>
      <div class="dropdown-menu">
        <h6 class="dropdown-header">Date created</h6>
        <button class="dropdown-item item-sorting-option active" data-sorting-option="date_created_old" type="button">Oldest</button>
        <button class="dropdown-item item-sorting-option" data-sorting-option="date_created_new" type="button">Newest</button>
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header">Alphabetically</h6>
        <button class="dropdown-item item-sorting-option" data-sorting-option="name_asc" type="button">Ascending</button>
        <button class="dropdown-item item-sorting-option" data-sorting-option="name_desc" type="button">Descending</button>
      </div>
    </div>

  </div>

  <!-- item search bar -->
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class='bx bx-search'></i></span>
    </div>
    <input type="text" class="form-control" id="item-search-input" placeholder="Search for an item...">
    <div class="input-group-append">
      <button class="btn btn-outline-secondary" type="button" onclick="clearItemSearchInput()"><i class='bx bx-x'></i></button>
    </div>
  </div>
</div>


<!-- item cards go here -->
<div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-4" id="items-deck"></div>


<!-- item modal -->
<div class="modal fade" id="item-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <!-- header -->
      <div class="modal-header">
        <h2 class="modal-title custom-font">Item</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <!-- body -->
      <div class="modal-body">

        <!-- info -->
        <div id="item-pills-info">

          <div class="row">

            <div class="col-9">

              <div class="info-section name">
                <div class="heading">
                  <div class="icon"><i class='bx bx-card'></i></div>
                  <div class="description">Item name</div>
                </div>
                <div class="panel content"></div>
              </div>

              <div class="info-section date-due">
                <div class="heading">
                  <div class="icon"><i class='bx bx-time'></i></div>
                  <div class="description">Date due</div>
                </div>
                <div class="panel content"></div>
              </div>

              <div class="info-section description">
                <div class="heading">
                  <div class="icon"><i class='bx bx-detail'></i></div>
                  <div class="description">Description</div>
                </div>
                <div class="panel content"></div>
              </div>

            </div>

            <div class="col-3">
              <h6>Actions</h6>

              <div class="list-group list-group-flush">

                <!-- set item to complete or incomplete -->
                <button type="button" class="list-group-item list-group-item-action" id="complete-item-modal-button" onclick="updateItemCompleted()"></button>

                <!-- edit item info button -->
                <div class="dropleft">
                  <button type="button" class="list-group-item list-group-item-action" data-toggle="dropdown">
                    <i class='bx bx-pencil'></i>&nbsp;Edit
                  </button>
                  <div class="dropdown-menu dropdown-form" id="edit-item-info-menu">
                    <h5>Edit item info</h5>

                    <!-- name -->
                    <div class="form-group">
                      <label for="edit-item-name">Name:</label>
                      <input type="text" id="edit-item-name" class="form-control update-button" data-button-id="#edit-item-description-btn">
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

                    <!-- update item info button -->
                    <button type="button" class="btn btn-primary float-right" id="edit-item-description-btn" onclick="updateItemInfo()">Save</button>
                  </div>
                </div>

                <!-- delete item button -->
                <button type="button" class="list-group-item list-group-item-action" onclick="deleteItem()">
                  <i class='bx bx-trash'></i>&nbsp;Delete
                </button>


              </div>
            </div>

          </div>

        </div>

        <!-- checklists -->
        <div id="item-pills-checklists">

          <!-- title and header -->
          <div class="info-section checklists">

            <!-- heading -->
            <div class="split">
              <div class="left">
                <div class="heading">
                  <div class="icon"><i class='bx bx-list-check'></i></div>
                  <div class="description">Checklists</div>
                </div>
              </div>
              <div class="right">
                <button type="button" class="btn btn-sm btn-secondary btn-show-details">Show details</button>
              </div>
            </div>

            <!-- panel -->
            <div class="panel d-none">
              <div class="row">
                <div class="col-3">

                  <!-- <h6>Current lists</h6> -->
                  <!-- side bar of item checklists -->
                  <ul class="nav nav-pills flex-column sidebar-item-checklists"></ul>

                  <!-- new project checklist dropdown -->
                  <div class="dropright">
                    <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown">Add checklist</button>
                    <div class="dropdown-menu">
                      <form>
                        <div class="form-group">
                          <label for="new-item-checklist-name">Name:</label>
                          <input type="text" id="new-item-checklist-name" class="form-control update-button" data-button-id="#new-item-checklist-btn">
                        </div>
                        <button id="new-item-checklist-btn" type="button" class="btn btn-primary float-right" disabled>Add</button>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- open checklists go here -->
                <div class="col-9" id="item-checklists"></div>
              </div>
            </div>
          </div>

        </div>


        <!-- notes -->
        <div id="item-pills-notes">

          <div class="info-section notes">

            <div class="split">
              <div class="left">
                <div class="heading">
                  <div class="icon"><i class='bx bx-note'></i></div>
                  <div class="description">Notes</div>
                </div>
              </div>
              <div class="right">
                <button type="button" class="btn btn-sm btn-secondary btn-show-details">Show details</button>
              </div>
            </div>


            <div>


              <div class="panel d-none">
                <!-- new note input -->
                <form>
                  <div class="form-group">
                    <!-- <label for="new-item-note-input">New note:</label> -->
                    <textarea id="new-item-note-input" class="form-control update-button" data-button-id="#new-item-note-btn" rows="5" placeholder="Write a note..."></textarea>
                  </div>

                  <button type="button" class="btn btn-primary" id="new-item-note-btn" onclick="addItemNote()" disabled>Save</button>
                </form>

                <!-- item note cards -->
                <div id="item-notes-cards"></div>
              </div>


            </div>



          </div>
        </div>
      </div>
      

    </div>
  </div>
</div>
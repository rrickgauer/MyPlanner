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

<div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-4" id="items-deck">

  <div class="col">
    <div class="card item-card">
      <div class="card-header">
        <div class="left">
          <h5>Item name</h5>
          <div class="date-created">11/12/2020 &bull; 5:23am</div>
        </div>

        <div class="right">
          <i class='bx bx-check-circle'></i>
        </div>

      </div>
      
      <div class="card-body item-card-stats">
        <div class="row">
          <div class="card">
            <div class="card-body">
              26 checklists
            </div>
          </div>


          <div class="card">
            <div class="card-body">
              11 Notes
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              Date due: 11/2/2021
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              Labels
            </div>
          </div>
        </div>

      </div>

      <div class="card-footer">
        <button type="button" class="btn btn-primary float-right">View</button>
      </div>
    </div>
  </div>



  

</div>






























































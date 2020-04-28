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


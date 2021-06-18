<?php
if($ADCheck == '0'){
?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><u>Add Part Type:</u></h1>
    <form autocomplete="off" enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
        <div class="container-fluid">
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="Type">Type:</label>
                    <input type="text" required="required" class="form-control" name="Type" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="Description">Description:</label>
                    <input type="text" required="required" class="form-control" name="Description" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="Status">Status:</label>
                    <select class="form-control" required="required" name="Status">
                        <option disabled="disabled" selected="selected" value="">Pick An Option</option>
                        <option value="1">Active</option>
                        <option value="2">Disabled</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" style="width:100%; position:relative; margin-top:30px;" name="AddPartType" value="Create">  
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800"><u>Part Type List:</u></h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="getPartType" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<?php
}
?>
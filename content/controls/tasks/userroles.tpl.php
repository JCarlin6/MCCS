<?php
if( (!empty($PermissionCheck) OR ($PermissionCheck == '0')) AND ($PermissionCheck < '3') ){
?>

<!-- Begin Page Content -->
<div class="container-fluid">

<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
    <div class="row" style="margin-bottom: 15px;">
        <div class="col">
            <input type="text" required="required" class="form-control" name="Role" placeholder="Role" value="">
        </div>
        <div class="col">
        <?php
            echo "<select required=\"required\" class=\"form-control\" name=\"Groups\">";
                echo "<option disabled=\"disabled\" selected=\"selected\">Select a Group...</option>";
                foreach($content->getAssignableGroups() AS $AssignableGroup){
                    echo "<option value=\"$AssignableGroup->id\">$AssignableGroup->Description</option>";
                }
            echo "</select>";
        ?>
        </div>
        <div class="col">
            <select required="required" class="form-control" name="Level">
                <option disabled="disabled" selected="selected">Select a Permission Level...</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
        </div>
        <div class="col">
            <?php
                echo "<select required=\"required\" class=\"form-control\" name=\"Facility\">";
                    echo "<option disabled=\"disabled\" selected=\"selected\">Select a Facility...</option>";
                    foreach($content->ListFacilities() AS $Facility){
                        echo "<option value=\"$Facility->id\">$Facility->Name</option>";
                    }
                echo "</select>";
            ?>
        </div>
        <div class="col">
            <input type="submit" class="btn btn-success" style="margin-right: 10px; width:100%;" name="AddRoleGroup" value="Add">
        </div>
    </div>
</form>

<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
<!-- Page Heading -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800"><u>User Roles:</u></h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="getUserRoles" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>Role</th>
                            <th>Group</th>
                            <th>Level</th>
                            <th>Facility</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Role</th>
                            <th>Group</th>
                            <th>Level</th>
                            <th>Facility</th>
                            <th></th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" class="btn btn-danger" style="float: right; margin-right: 10px;" name="UserRolesDelete" value="Delete">
            </div>
        </div>
	</form>
</div>

<?php
}
?>
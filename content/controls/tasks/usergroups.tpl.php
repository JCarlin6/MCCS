<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><u>Modify User Roles:</u></h1>
    <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
        <div class="container-fluid">
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="AssignedGroup">Assigned Group</label>
                    <select class="form-control" required="required" id="AssignedGroup" name="AssignedGroup">
                    <?php 
                    if(is_null($WorkorderMainContent[0]->AssignedGroup)){
                        echo "<option selected=\"selected\" disabled=\"disabled\">**Select a Group**</option>";
                    }
                        $AssignmentTypeArray = $content->getAssignableRoles();
                        foreach($AssignmentTypeArray AS $AssignmentTypeItem){
                            if($WorkorderMainContent[0]->AssignedGroup == $AssignmentTypeItem->id){
                                echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
                            } else {
                                echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
                            }
                        }
                        unset($AssignmentTypeArray,$AssignmentTypeItem);
                    ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="AssignedUserInput">Assigned User</label>
                    <input type="text" list="AssignedUserList" id="AssignedUserInput" class="form-control" autocomplete="off" placeholder="**Select User**" name="AssignedUserInput" value="">
                    <datalist id="AssignedUserList">
                    <?php
                        foreach($content->getUserListWithSelf() AS $UserListItem){
                                echo "<option data-value=\"$UserListItem->UID\">$UserListItem->FirstName $UserListItem->LastName</option>";
                        }
                        echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\">";
                    ?>
                    </datalist>
                </div>
            </div>
            <div class="col">
                <input type="submit" class="btn btn-warning" style="width:100%; position:relative; margin-top:31px;" name="AssignUser" value="Assign User">    
            </div>
        </div>

        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800"><u>Role List:</u></h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="getRoleList" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>Employee</th>
                            <th>Group</th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Employee</th>
                            <th>Group</th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
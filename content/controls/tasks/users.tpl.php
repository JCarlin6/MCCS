<?php
if($ADCheck == '0'){
?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><u>Add User:</u></h1>
    <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
        <div class="container-fluid">
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="FirstName">First Name:</label>
                    <input type="text" required="required" class="form-control" name="FirstName" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="LastName">Last Name:</label>
                    <input type="text" required="required" class="form-control" name="LastName" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="UserName">Username:</label>
                    <input type="text" required="required" class="form-control" name="UserName" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="Password">Password:</label>
                    <input type="password" required="required" class="form-control" name="Password" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" required="required" class="form-control" name="Email" value="">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" style="width:100%; position:relative; margin-top:30px;" name="AddUser" value="Create">  
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800"><u>User List:</u></h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="getUserList" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Active</th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Active</th>
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
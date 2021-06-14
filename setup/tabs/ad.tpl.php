<div class="row">
    <div class="col text-center">
      <h2>Active Directory / User Information</h2>
    </div>
  </div>
  <div class="row justify-content-center">
        <div class="form-group col-4">
            <div class="form-group" id="UserControllerDiv">
                <label for="AssignmentType">User Infrastructure</label>
                <select onchange="ShowADDetail();" class="form-control" id="UserController">
                    <option disabled="disabled" selected="selected">**Choose Infrastructure Type**</option>
                    <option value='1'>Active Directory</option>
                    <option value='2'>Internal User Control</option>
                </select>
            </div>
        </div>
    </div>

  <div id="UserPath" style="display:none;">
    <form enctype="multipart/form-data" action="/setup/logic.php?" method="post" class="text-center" id="UserPathForm">
        <div class="row justify-content-center">
            <div class="form-group col-4">
                <label for="exampleInputEmail1">Admin Username</label>
                <input type="text" class="form-control" id="AdminUsername" aria-describedby="emailHelp" placeholder="admin">
            </div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="form-group col-4">
                <label for="exampleInputEmail1">Admin Password</label>
                <input type="password" class="form-control" id="AdminPassword" aria-describedby="emailHelp" placeholder="********">
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="UserSubmit" onclick="SubmitUserInfo();">Submit</button>
    </form>
</div>

<div id="ADPath" style="display:none;">
    <form enctype="multipart/form-data" action="/setup/logic.php?" method="post" class="text-center" id="ADPathForm">
        <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="exampleInputEmail1">FQDN</label>
            <input type="text" class="form-control" id="FQDN" aria-describedby="emailHelp" placeholder="myawesomecompany.com">
        </div>
        </div>
        <div class="row">
        <div class="col-4"></div>
        <div class="form-group col-4">
            <label for="exampleInputEmail1">LDAP Server IP Address</label>
            <input type="text" class="form-control" id="LDAPServer" aria-describedby="emailHelp" placeholder="172.0.0.1">
        </div>
        </div>
        <button type="button" class="btn btn-primary" id="ADSubmit" onclick="SubmitADInfo();">Submit</button>
    </form>
</div>
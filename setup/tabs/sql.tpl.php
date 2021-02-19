<div class="row">
    <div class="col text-center">
      <h2>MySQL Information</h2>
    </div>
  </div>
  <form enctype="multipart/form-data" action="/setup/logic.php?" method="post" class="text-center" id="SQLForm">
    <div class="row justify-content-center">
      <div class="form-group col-4">
        <label for="exampleInputEmail1">Server Address</label>
        <input type="text" class="form-control" id="MySQLServerAddress" aria-describedby="emailHelp" placeholder="172.0.0.1" value="localhost">
      </div>
    </div>
    <div class="row">
      <div class="col-4"></div>
      <div class="form-group col-4">
        <label for="exampleInputEmail1">MySQL Database</label>
        <input type="text" class="form-control" id="MySQLDB" aria-describedby="emailHelp" placeholder="DatabaseName" value="mccs">
      </div>
    </div>
    <div class="row">
      <div class="col-4"></div>
      <div class="form-group col-4">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" class="form-control" id="MySQLUsername" aria-describedby="emailHelp" placeholder="My Username" value="jcarlin">
      </div>
    </div>
    <div class="row">
      <div class="col-4"></div>
      <div class="form-group col-4">
        <label for="exampleInputEmail1">Password</label>
        <input type="password" class="form-control" id="MySQLPassword" aria-describedby="emailHelp" placeholder="********" value="M0nk3y!!">
      </div>
    </div>
    <button type="button" class="btn btn-primary" id="SQLDetails" onclick="SubmitSQLInfo();">Submit</button>
  </form>
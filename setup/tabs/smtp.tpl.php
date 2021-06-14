<form enctype="multipart/form-data" action="/setup/logic.php?" method="post" class="text-center" id="SMTPForm">
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="exampleInputEmail1">SMTP Server Address</label>
            <input type="text" class="form-control" id="SMTPServerAddress" aria-describedby="SMTPServer" placeholder="172.0.0.1">
        </div>
    </div>
    <button type="button" class="btn btn-primary" id="SMTPServer" onclick="SubmitSMTPServer();">Submit</button>
</form>
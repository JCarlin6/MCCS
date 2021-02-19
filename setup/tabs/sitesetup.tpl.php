<form enctype="multipart/form-data" action="/setup/logic.php?" method="post" class="text-center" id="SiteForm">
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="DefaultSiteName">Default Site Name</label>
            <input type="text" class="form-control" id="DefaultSiteName" aria-describedby="DefaultSiteName" placeholder="Default Site">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="Street">Street</label>
            <input type="text" class="form-control" id="Street" aria-describedby="Street" placeholder="123 Sesame St.">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="City">City</label>
            <input type="text" class="form-control" id="City" aria-describedby="City" placeholder="Howell">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="State">State</label>
            <input type="text" class="form-control" id="State" aria-describedby="State" placeholder="MI">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-4">
            <label for="Country">Country</label>
            <input type="text" class="form-control" id="Country" aria-describedby="Country" placeholder="United States">
        </div>
    </div>
    <button type="button" class="btn btn-primary" id="DefaultSiteAdd" onclick="DefaultSite();">Submit</button>
</form>
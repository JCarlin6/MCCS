<?php 
$UID = $_SESSION["UID"];
$PermissionCheckArray = $content->UserPermissionCheck($UID);
$PermissionCheck = $PermissionCheckArray[0]->Level;
if((empty($PermissionCheck) AND $PermissionCheck != '0') OR ($PermissionCheck > '3')){
  $PermissionCheck = '666';
}

$CheckifADorLocal = $content->ADorLocal();
$ADCheck = $CheckifADorLocal[0]->ActiveDirectory;
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="default.php">
        <div id="logo"></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="default.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Home</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Modules
      </div>

      <!-- Nav Item - Workorders Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Workorders</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Processing:</h6>
            <a class="collapse-item" href="default.php?do=workorders&action=create">Create</a>
            <h6 class="collapse-header">Workorders:</h6>
            <a class="collapse-item" href="default.php">All</a>
            <a class="collapse-item" href="default.php?do=workorders&action=employee">My Workorders + PM's</a>
            <a class="collapse-item" href="default.php?do=workorders&action=team">Team Workorders + PM's</a>
            <?php if($PermissionCheck != '666'){ ?>
            <a class="collapse-item" href="default.php?do=workorders&action=pendingappr">Pending Approval</a>
            <a class="collapse-item" href="default.php?do=workorders&action=open">Open</a>
            <a class="collapse-item" href="default.php?do=workorders&action=in-process">In-Process</a>
            <a class="collapse-item" href="default.php?do=workorders&action=scheduled">Scheduled</a>
            <a class="collapse-item" href="default.php?do=workorders&action=declined">Declined</a>
            <a class="collapse-item" href="default.php?do=workorders&action=closed">Closed</a>
            <h6 class="collapse-header">PM's:</h6>
            <a class="collapse-item" href="default.php?do=workorders&action=activepm">Active PM's</a>
            <a class="collapse-item" href="default.php?do=workorders&action=pendingcrib">Pending Crib</a>
            <a class="collapse-item" href="default.php?do=workorders&action=pendingsuper">Pending Supervisor</a>
            <a class="collapse-item" href="default.php?do=workorders&action=openpm">Open PM's</a>
            <a class="collapse-item" href="default.php?do=workorders&action=overduepm">Overdue PM's</a>
            <a class="collapse-item" href="default.php?do=workorders&action=closedpm">Closed PM's</a>
            <?php } ?>
          </div>
        </div>
      </li>

      <!-- Nav Item - Assets Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-database"></i>
          <span>Inventory</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Processing:</h6>
            <?php
              if($PermissionCheck != '666'){
            ?>
            <a class="collapse-item" href="inventory.php?do=inventory&action=create">Create</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=manage">Manage</a>
            <?php
              }
            ?>
            <a class="collapse-item" href="inventory.php?do=inventory&action=withdraw">Withdraw</a>
            <?php
              if($PermissionCheck != '666'){
            ?>
            <h6 class="collapse-header">Orders:</h6>
            <a class="collapse-item" href="inventory.php?do=inventory&action=order">Order</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=orderstoreceive">Receive</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=orderspending">Pending</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=fulfilled">Fulfilled</a>
            <h6 class="collapse-header">Views:</h6>
            <a class="collapse-item" href="inventory.php?do=inventory&action=all">All</a>
            <!-- 
            <a class="collapse-item" href="inventory.php?do=inventory&action=viewbulk">Bulk</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=viewindividual">Individual</a> 
            -->
            <a class="collapse-item" href="inventory.php?do=inventory&action=oos">Out-of-stock</a>
            <a class="collapse-item" href="inventory.php?do=inventory&action=low">Low</a>
            <?php
              } 
            ?>
          </div>
        </div>
      </li>
      <?php
              if($PermissionCheck != '666'){
            ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAssets" aria-expanded="true" aria-controls="collapseAssets">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Assets</span>
        </a>
        <div id="collapseAssets" class="collapse" aria-labelledby="headingAssets" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Processing:</h6>
            <a class="collapse-item" href="assets.php?do=assets&action=create">Create</a>
            <h6 class="collapse-header">Views:</h6>
            <a class="collapse-item" href="assets.php?do=assets&action=all">All</a>
            <a class="collapse-item" href="assets.php?do=assets&action=active">Active</a>
            <a class="collapse-item" href="assets.php?do=assets&action=inactive">Inactive</a>
            <a class="collapse-item" href="assets.php?do=assets&action=ooo">Out-of-order</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVendors" aria-expanded="true" aria-controls="collapseVendors">
          <i class="fas fa-fw fa-shopping-cart"></i>
          <span>Vendors</span>
        </a>
        <div id="collapseVendors" class="collapse" aria-labelledby="collapseVendors" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Processing:</h6>
            <a class="collapse-item" href="vendors.php?do=vendors&action=create">Create</a>
            <h6 class="collapse-header">Views:</h6>
            <a class="collapse-item" href="vendors.php?do=vendors&action=all">All</a>
            <a class="collapse-item" href="vendors.php?do=vendors&action=active">Active</a>
            <a class="collapse-item" href="vendors.php?do=vendors&action=inactive">Inactive</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapselocations" aria-expanded="true" aria-controls="collapselocations">
          <i class="fas fa-fw fa-globe"></i>
          <span>Locations</span>
        </a>
        <div id="collapselocations" class="collapse" aria-labelledby="headinglocations" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Inventory Locations:</h6>
            <a class="collapse-item" href="locations.php?do=inventory&action=all">All - Manage</a>
            <h6 class="collapse-header">Asset Locations:</h6>
            <a class="collapse-item" href="locations.php?do=assets&action=all">All - Manage</a>
            <a class="collapse-item" href="locations.php?do=assets&action=active">Active</a>
            <a class="collapse-item" href="locations.php?do=assets&action=disabled">Disabled</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseaudit" aria-expanded="true" aria-controls="collapseaudit">
          <i class="fas fa-fw fa-check"></i>
          <span>Audit</span>
        </a>
        <div id="collapseaudit" class="collapse" aria-labelledby="headinglocations" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Functions:</h6>
            <a class="collapse-item" href="audit.php?do=audit&action=location">Inventory Location</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports" aria-expanded="true" aria-controls="collapseReports">
          <i class="fas fa-fw fa-paperclip"></i>
          <span>Reports</span>
        </a>
        <div id="collapseReports" class="collapse" aria-labelledby="headinglocations" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Workorder / PM's:</h6>
            <a class="collapse-item" href="reports.php?do=reporting&action=upcomingpm">Upcoming PM's</a>
            <a class="collapse-item" href="reports.php?do=reporting&action=pastduepm">Past Due PM's</a>
            <a class="collapse-item" href="reports.php?do=reporting&action=pastduewo">Past Due Workorders</a>
            <h6 class="collapse-header">Assets:</h6>
            <a class="collapse-item" href="reports.php?do=reporting&action=pmconducted">PM's Conducted</a>
            <a class="collapse-item" href="reports.php?do=reporting&action=quantitiesused">Part Quantities Used</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseControls" aria-expanded="true" aria-controls="collapseControls">
          <i class="fas fa-fw fa-paperclip"></i>
          <span>Control Panel</span>
        </a>
        <div id="collapseControls" class="collapse" aria-labelledby="headinglocations" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">User Control:</h6>
            <?php
              if($ADCheck == '0'){
                echo "<a class=\"collapse-item\" href=\"controls.php?do=controller&action=users\">Users</a>";
              } 
              if( (!empty($PermissionCheck) OR ($PermissionCheck == '0')) AND ($PermissionCheck < '3') ){
                echo "<a class=\"collapse-item\" href=\"controls.php?do=controller&action=usergroups\">Assignable Roles</a>";
                echo "<a class=\"collapse-item\" href=\"controls.php?do=controller&action=userroles\">User Roles</a>";
                echo "<a class=\"collapse-item\" href=\"controls.php?do=controller&action=groups\">Groups</a>";
              }
            ?>
            <h6 class="collapse-header">Content Control:</h6>
            <?php
              echo "<a class=\"collapse-item\" href=\"controls.php?do=controller&action=parttype\">Part Type</a>";
            ?>
          </div>
        </div>
      </li>
<?php
}
?>
    </ul>
    <!-- End of Sidebar -->



        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <form class="form-inline">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">



      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php $Username = str_replace(".", " ", $_SESSION['Username']); echo ucwords($Username); ?></span>
          <i class="fa fa-id-badge" aria-hidden="true"></i>
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Settings
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
            Activity Log
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
          </a>
        </div>
      </li>

    </ul>

  </nav>
  <!-- End of Topbar -->
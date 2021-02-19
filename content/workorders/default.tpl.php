<?php 
if(isset($AlertBar)){
  if ($AlertBar != NULL){ ?>
    <div id="alert" class="alert alert-<?php echo $ErrorType;?>">
      <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
    </div>
  <?php 
  } 
}?>

<?php switch(Filter::$action): case "open": ?>
  <?php include 'tasks/open.tpl.php'; ?>
<?php break;?>
<?php case "pending":?>
  <?php include 'tasks/pending.tpl.php'; ?>
<?php break;?>
<?php case "pendingappr":?>
  <?php include 'tasks/pending.tpl.php'; ?>
<?php break;?>
<?php case "declined":?>
  <?php include 'tasks/declined.tpl.php'; ?>
<?php break;?>
<?php case "scheduled":?>
  <?php include 'tasks/scheduled.tpl.php'; ?>
<?php break;?>
<?php case "in-process":?>
  <?php include 'tasks/inprocess.tpl.php'; ?>
<?php break;?>
<?php case "create":?>
  <?php include 'tasks/create.tpl.php'; ?>
<?php break;?>
<?php case "closed":?>
  <?php include 'tasks/closed.tpl.php'; ?>
<?php break;?>
<?php case "employee":?>
  <?php include 'tasks/employee.tpl.php'; ?>
<?php break;?>
<?php case "team":?>
  <?php include 'tasks/team.tpl.php'; ?>
<?php break;?>
<?php case "view":?>
  <?php include 'tasks/view.tpl.php'; ?>
<?php break;?>
<?php case "viewpm":?>
  <?php include 'tasks/preventative/view.tpl.php'; ?>
<?php break;?>
<?php case "openpm":?>
  <?php include 'tasks/preventative/open.tpl.php'; ?>
<?php break;?>
<?php case "pendingcrib":?>
  <?php include 'tasks/preventative/pendingcrib.tpl.php'; ?>
<?php break;?>
<?php case "pendingsuper":?>
  <?php include 'tasks/preventative/pendingsuper.tpl.php'; ?>
<?php break;?>
<?php case "overduepm":?>
  <?php include 'tasks/preventative/overduepm.tpl.php'; ?>
<?php break;?>
<?php case "closedpm":?>
  <?php include 'tasks/preventative/closedpm.tpl.php'; ?>
<?php break;?>
<?php case "activepm":?>
  <?php include 'tasks/preventative/active.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


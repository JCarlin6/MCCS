<?php switch(Filter::$action): case "usergroups": ?>
  <?php include 'tasks/usergroups.tpl.php'; ?>
<?php break;?>
<?php case "users":?>
  <?php include 'tasks/users.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


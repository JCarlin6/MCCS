<?php switch(Filter::$action): case "all": ?>
  <?php include 'assets/all.tpl.php'; ?>
<?php break;?>
<?php case "active":?>
  <?php include 'assets/active.tpl.php'; ?>
<?php break;?>
<?php case "disabled":?>
  <?php include 'assets/disabled.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'viewassets.tpl.php'; ?>
<?php endswitch;?>


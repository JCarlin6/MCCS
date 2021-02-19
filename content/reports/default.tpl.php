<?php switch(Filter::$action): case "pastduepm": ?>
  <?php include 'tasks/pastduepm.tpl.php'; ?>
<?php break;?>
<?php case "pastduewo":?>
  <?php include 'tasks/pastduewo.tpl.php'; ?>
<?php break;?>
<?php case "pmconducted":?>
  <?php include 'tasks/pmconducted.tpl.php'; ?>
<?php break;?>
<?php case "quantitiesused":?>
  <?php include 'tasks/quantitiesused.tpl.php'; ?>
<?php break;?>
<?php case "upcomingpm":?>
  <?php include 'tasks/upcomingpm.tpl.php'; ?>
<?php break;?>
<?php case "closed":?>
  <?php include 'tasks/closed.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


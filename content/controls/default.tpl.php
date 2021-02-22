<?php if (isset($AlertBar)){ ?>
  <div id="alert" class="alert alert-<?php echo $ErrorType; ?>">
    <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
  </div>
<?php } ?>

<?php switch(Filter::$action): case "usergroups": ?>
  <?php include 'tasks/usergroups.tpl.php'; ?>
<?php break;?>
<?php case "users":?>
  <?php include 'tasks/users.tpl.php'; ?>
<?php break;?>
<?php case "userroles":?>
  <?php include 'tasks/userroles.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


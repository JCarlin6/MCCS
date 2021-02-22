<?php if (isset($AlertBar)){ ?>
  <div id="alert" class="alert alert-<?php echo $ErrorType; ?>">
    <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
  </div>
<?php } ?>

<?php switch(Filter::$action): case "view": ?>
  <?php include 'tasks/view.tpl.php'; ?>
<?php break;?>
<?php case "create":?>
  <?php include 'tasks/create.tpl.php'; ?>
<?php break;?>
<?php case "active":?>
  <?php include 'tasks/viewactive.tpl.php'; ?>
<?php break;?>
<?php case "inactive":?>
  <?php include 'tasks/viewinactive.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


<?php if (isset($AlertBar)){ ?>
  <div id="alert" class="alert alert-<?php echo $ErrorType;?>">
    <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
  </div>
<?php } ?>

<?php switch(Filter::$action): case "location": ?>
  <?php include 'tasks/location.tpl.php'; ?>
<?php break;?>
<?php case "locationaudit":?>
  <?php include 'tasks/locationaudit.tpl.php'; ?>
<?php break;?>
<?php case "disabled":?>
  <?php include 'tasks/disabled.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'viewassets.tpl.php'; ?>
<?php endswitch;?>


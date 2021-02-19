<?php if (isset($AlertBar)){ ?>
  <div id="alert" class="alert alert-<?php echo $ErrorType;?>">
    <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
  </div>
<?php } ?>

<?php switch(Filter::$action): case "all": ?>
  <?php include 'inventory/all.tpl.php'; ?>
<?php break;?>
<?php case "active":?>
  <?php include 'inventory/active.tpl.php'; ?>
<?php break;?>
<?php case "disabled":?>
  <?php include 'inventory/disabled.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'viewassets.tpl.php'; ?>
<?php endswitch;?>


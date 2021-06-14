<?php if (isset($AlertBar)){ ?>
  <div id="alert" class="alert alert-<?php echo $ErrorType;?>">
    <strong><?php echo $MSGHeader; ?></strong> <?php echo $MSGText; ?>
  </div>
<?php } ?>

<?php switch(Filter::$action): case "open": ?>
  <?php include 'tasks/open.tpl.php'; ?>
<?php break;?>
<?php case "pending":?>
  <?php include 'tasks/pending.tpl.php'; ?>
<?php break;?>
<?php case "closed":?>
  <?php include 'tasks/closed.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


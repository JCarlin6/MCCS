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
<?php case "order":?>
  <?php include 'tasks/order.tpl.php'; ?>
<?php break;?>
<?php case "receive":?>
  <?php include 'tasks/receive.tpl.php'; ?>
<?php break;?>
<?php case "orderstoreceive":?>
  <?php include 'tasks/orderstoreceive.tpl.php'; ?>
<?php break;?>
<?php case "orderspending":?>
  <?php include 'tasks/orderspending.tpl.php'; ?>
<?php break;?>
<?php case "vieworder":?>
  <?php include 'tasks/vieworder.tpl.php'; ?>
<?php break;?>
<?php case "viewbulk":?>
  <?php include 'tasks/bulk.tpl.php'; ?>
<?php break;?>
<?php case "history":?>
  <?php include 'tasks/history.tpl.php'; ?>
<?php break;?>
<?php case "withdraw":?>
  <?php include 'tasks/withdraw.tpl.php'; ?>
<?php break;?>
<?php case "low":?>
  <?php include 'tasks/low.tpl.php'; ?>
<?php break;?>
<?php case "viewindividual":?>
  <?php include 'tasks/individual.tpl.php'; ?>
<?php break;?>
<?php case "oos":?>
  <?php include 'tasks/outofstock.tpl.php'; ?>
<?php break;?>
<?php case "create":?>
  <?php include 'tasks/create.tpl.php'; ?>
<?php break;?>
<?php case "view":?>
  <?php include 'tasks/view.tpl.php'; ?>
<?php break;?>
<?php case "closed":?>
  <?php include 'tasks/closed.tpl.php'; ?>
<?php break;?>
<?php case "fulfilled":?>
  <?php include 'tasks/fulfilled.tpl.php'; ?>
<?php break;?>
<?php default; ?>
  <?php include 'main.tpl.php'; ?>
<?php endswitch;?>


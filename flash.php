<?php if (isset($_SESSION['flash_flag']) && $_SESSION['flash_flag']): ?>
<div class="container">
  <div id="flash" class="alert alert-<?=$_SESSION['status']?> alert-dismissable fade in" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$_SESSION['flash_msg']?>
    <?php $_SESSION['flash_flag'] = false; ?>
  </div>
</div>
<?php endif; ?>
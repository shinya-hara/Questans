<?php if (isset($_SESSION['flash_flag']) && $_SESSION['flash_flag']): ?>
<div class="alert alert-<?=$_SESSION['status']?>" role="alert">
  <?=$_SESSION['flash_msg']?>
  <?php $_SESSION['flash_flag'] = false; ?>
</div>
<?php endif; ?>
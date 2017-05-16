<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>管理画面 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>管理画面</h2>
      <?php if (isset($_SESSION['flash_flag']) && $_SESSION['flash_flag']): ?>
      <div class="alert alert-<?=$_SESSION['status']?>" role="alert">
        <?=$_SESSION['flash_msg']?>
        <?php $_SESSION['flash_flag'] = false; ?>
      </div>
      <?php endif; ?>
      <a href="make.php"><input type="button" value="アンケート作成画面へ" class="btn btn-default"></a>
    </div>
  </body>
</html>
<?php
$_SESSION = array();
?>
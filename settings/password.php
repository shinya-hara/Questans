<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "タイトルの取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <title>設定 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/../header.php'; ?>
    <?php include __DIR__.'/../guest_alert.php'; ?>
    <div class="container">
      <?php include __DIR__.'/../flash.php'; ?>
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">設定項目</h3>
            </div>
            <div class="list-group">
              <a href="nickname.php" class="list-group-item"><i class="fa fa-address-card-o" aria-hidden="true"></i> 表示名</a>
              <a href="" class="list-group-item list-group-item-info"><i class="fa fa-key fa-fw" aria-hidden="true"></i> <strong>パスワード</strong></a>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">パスワード変更</h3>
            </div>
            <div class="panel-body">
              <div id="msg"></div>
              <form>
              	<div class="form-group">
              		<label class="control-label" for="old-password">現在のパスワード</label>
            			<input type="password" class="form-control" name="old-password" <?=($_SESSION['username']==='guest')?'disabled':'required'?>>
              	</div>
              	<div class="form-group">
              		<label class="control-label" for="new-password">新しいパスワード</label>
              		<input type="password" class="form-control" name="new-password" placeholder="4文字以上" <?=($_SESSION['username']==='guest')?'disabled':'required'?>>
              		<p class="help-block">半角英数字のみ使用できます．</p>
              	</div>
              	<div class="form-group">
              		<label class="control-label" for="check-password">新しいパスワード（確認）</label>
              		<input type="password" class="form-control" name="check-password" <?=($_SESSION['username']==='guest')?'disabled':'required'?>>
              	</div>
              	<input type="hidden" name="token" value="<?=h(generate_token())?>">
              	<div class="form-group">
            			<button type="submit" class="btn btn-success" id="password-update" data-loading-text="保存中..." <?=($_SESSION['username']==='guest')?'disabled':''?>>変更を保存する</button>
              	</div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /container -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/5bf7a4a25c.js"></script>
    <script>
    $(function(){
      $('form').submit(function(event){
        event.preventDefault();
        var $btn = $('#password-update').button('loading');
        $.post('password_update.php',
        $(this).serialize(),
        function(data) {
          if (data === "") {
            window.location.href = "/management.php";
          } else {
            $btn.button('reset');
            $('#msg').html(data);
          }
        });
      });
    });
    </script>
  </body>
</html>
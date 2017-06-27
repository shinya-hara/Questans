<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("SELECT nickname FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $nickname = $stmt->fetch();
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
    <div class="container">
      <?php include __DIR__.'/../flash.php'; ?>
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">設定項目</h3>
            </div>
            <div class="list-group">
              <a href="" class="list-group-item list-group-item-info"><i class="fa fa-user fa-fw" aria-hidden="true"></i> <strong>表示名</strong></a>
              <a href="password.php" class="list-group-item"><i class="fa fa-key fa-fw" aria-hidden="true"></i> パスワード</a>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">表示名変更</h3>
            </div>
            <div class="panel-body">
              <div id="msg"></div>
            	<div class="form-group">
            		<label class="control-label" for="old-nickname">現在の表示名</label>
            		<p class="form-control-static"><?= ($nickname['nickname']===null) ? '（未設定）' : h($nickname['nickname']) ?></p>
            	</div>
            	<div class="form-group">
            		<label class="control-label" for="new-nickname">新しい表示名</label>
          			<input type="text" class="form-control" id="new-nickname" maxlength="20" placeholder="20文字まで">
          			<p class="help-block">新しい表示名を空欄で保存した場合，表示名は未設定になります．</p>
            	</div>
            	<div class="form-group">
          			<button type="button" id="nickname-update" class="btn btn-success" data-loading-text="保存中...">変更を保存する</button>
            	</div>
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
      $('#nickname-update').on('click', function() {
        var $btn = $(this).button('loading');
        $.post('nickname_update.php',
        {
          'new-nickname': $('#new-nickname').val()
        },
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
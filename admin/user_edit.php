<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // ユーザ情報の取得
    $stmt = $dbh->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_POST['user_id']]);
    $user = $stmt->fetch();
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
<div class="container">
  <a href="users_management.php" class="btn btn-default">戻る</a>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-user fa-fw" aria-hidden="true"></i> ユーザ名</h3>
    </div>
    <div class="panel-body">
    	<?=h($user['user_name'])?>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-address-card-o" aria-hidden="true"></i> 表示名変更</h3>
    </div>
    <div class="panel-body">
      <div id="msg-nickname"></div>
      <form id="nickname-form">
      	<div class="form-group">
      		<label class="control-label" for="old-nickname">現在の表示名</label>
      		<p class="form-control-static" id="current-nickname"><?= ($user['nickname']===null) ? '（未設定）' : h($user['nickname']) ?></p>
      	</div>
      	<div class="form-group">
      		<label class="control-label" for="new-nickname">新しい表示名</label>
    			<input type="text" class="form-control" name="new-nickname" maxlength="20" placeholder="20文字まで">
    			<p class="help-block">新しい表示名を空欄で保存した場合，表示名は未設定になります．</p>
      	</div>
      	<input type="hidden" name="user_id" value="<?=$_POST['user_id']?>">
      	<div class="form-group">
    			<button type="submit" id="nickname-update" class="btn btn-success" data-loading-text="保存中...">変更を保存する</button>
      	</div>
    	</form>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-key fa-fw" aria-hidden="true"></i> パスワード変更</h3>
    </div>
    <div class="panel-body">
      <div id="msg-password"></div>
      <form id="password-form">
      	<div class="form-group">
      		<label class="control-label" for="new-password">新しいパスワード</label>
      		<input type="password" class="form-control" name="new-password" placeholder="4文字以上" required>
      		<p class="help-block">半角英数字のみ使用できます．</p>
      	</div>
      	<div class="form-group">
      		<label class="control-label" for="check-password">新しいパスワード（確認）</label>
      		<input type="password" class="form-control" name="check-password" required>
      	</div>
      	<input type="hidden" name="user_id" value="<?=$_POST['user_id']?>">
      	<input type="hidden" name="token" value="<?=h(generate_token())?>">
      	<div class="form-group">
    			<button type="submit" class="btn btn-success" id="password-update" data-loading-text="保存中...">変更を保存する</button>
      	</div>
      </form>
    </div>
  </div>
</div>
<script>
$(function(){
  // 表示名変更
  $('#nickname-form').submit(function(event) {
    event.preventDefault();
    var $btn = $('#nickname-update').button('loading');
    $.post('nickname_update.php',
    $(this).serialize(),
    function(data) {
      $btn.button('reset');
      $('#msg-nickname').html(data);
      $.post('show_new_nickname.php',
      {
        'user_id': "<?=$_POST['user_id']?>"
      },
      function(data) {
        $('#current-nickname').text(data);
      });
    });
  });
  // パスワード変更
  $('#password-form').submit(function(event) {
    event.preventDefault();
    var $btn = $('#password-update').button('loading');
    $.post('password_update.php',
    $(this).serialize(),
    function(data) {
      $btn.button('reset');
      $('#msg-password').html(data);
    });
  });
});
</script>
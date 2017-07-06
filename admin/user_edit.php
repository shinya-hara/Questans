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
      <h3 class="panel-title">ユーザ名</h3>
    </div>
    <div class="panel-body">
    	<?=h($user['user_name'])?>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">表示名変更</h3>
    </div>
    <div class="panel-body">
      <div id="msg"></div>
    	<div class="form-group">
    		<label class="control-label" for="old-nickname">現在の表示名</label>
    		<p class="form-control-static"><?= ($user['nickname']===null) ? '（未設定）' : h($user['nickname']) ?></p>
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">パスワード変更</h3>
    </div>
    <div class="panel-body">
      <div id="msg"></div>
      <form>
      	<div class="form-group">
      		<label class="control-label" for="new-password">新しいパスワード</label>
      		<input type="password" class="form-control" name="new-password" placeholder="4文字以上" required>
      		<p class="help-block">半角英数字のみ使用できます．</p>
      	</div>
      	<div class="form-group">
      		<label class="control-label" for="check-password">新しいパスワード（確認）</label>
      		<input type="password" class="form-control" name="check-password" required>
      	</div>
      	<input type="hidden" name="token" value="<?=h(generate_token())?>">
      	<div class="form-group">
    			<button type="submit" class="btn btn-success" id="password-update" data-loading-text="保存中...">変更を保存する</button>
      	</div>
      </form>
    </div>
  </div>
</div>
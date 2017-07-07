<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_admin_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        validate_token(filter_input(INPUT_POST, 'token'))) {
      if (isset($_POST['new-password']) &&
          isset($_POST['check-password'])) {
        $new_password   = $_POST['new-password'];
        $check_password = $_POST['check-password'];
        
        // バリデーション
        if (strlen($new_password) < 4 || strlen($check_password) < 4) {
          // 短すぎ
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "パスワードが短すぎます．4文字以上にしてください．";
          $_SESSION['flash_flag'] = true;
        } else if (strcmp($new_password, $check_password) !== 0) {
          // パスワード不一致
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "パスワードが一致しません．";
          $_SESSION['flash_flag'] = true;
        } else {
          // OK
          $stmt = $dbh->prepare("UPDATE users SET password = ? WHERE user_id = ?");
          $stmt->bindValue(1, password_hash($new_password, PASSWORD_BCRYPT));
          $stmt->bindValue(2, $_POST['user_id']);
          $stmt->execute();
          $_SESSION['status'] = "success";
          $_SESSION['flash_msg'] = "パスワードを変更しました．";
          $_SESSION['flash_flag'] = true;
        }
      } else {
        // パラメータ不足
        $_SESSION['status'] = "danger";
        $_SESSION['flash_msg'] = "パスワードが正しく入力されていません．";
        $_SESSION['flash_flag'] = true;
      }
    } else {
      // 不正アクセス
      $_SESSION['status'] = "danger";
      $_SESSION['flash_msg'] = "不正なアクセスです．";
      $_SESSION['flash_flag'] = true;
    }
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "パスワードの変更に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<div id="flash" class="alert alert-<?=$_SESSION['status']?> alert-dismissable fade in" role="alert">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <?=$_SESSION['flash_msg']?>
  <?php $_SESSION['flash_flag'] = false; ?>
</div>
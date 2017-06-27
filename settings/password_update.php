<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        validate_token(filter_input(INPUT_POST, 'token'))) {
      if (isset($_POST['old-password']) &&
          isset($_POST['new-password']) &&
          isset($_POST['check-password'])) {
        $old_password   = $_POST['old-password'];
        $new_password   = $_POST['new-password'];
        $check_password = $_POST['check-password'];
        
        // 現在のパスワードが一致しているかチェック
        $stmt = $dbh->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $password = $stmt->fetch();
        // バリデーション
        if (!password_verify($old_password, $password['password'])) {
          // 現在のパスワードが不一致
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "現在のパスワードが正しくありません．";
          $_SESSION['flash_flag'] = true;
        } else if (strlen($new_password) < 4 ||
                   strlen($check_password) < 4) {
          // 短すぎ
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "パスワードが短すぎます．4文字以上にしてください．";
          $_SESSION['flash_flag'] = true;
        } else if (strcmp($new_password, $check_password) !== 0) {
          // パスワード不一致
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "新しいパスワードが一致しません．";
          $_SESSION['flash_flag'] = true;
        } else {
          // OK
          $stmt = $dbh->prepare("UPDATE users SET password = ? WHERE user_id = ?");
          $stmt->bindValue(1, password_hash($new_password, PASSWORD_BCRYPT));
          $stmt->bindValue(2, $_SESSION['user_id']);
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
<?php if ($_SESSION['status']==="danger"): ?>
<div id="flash" class="alert alert-danger" role="alert">
  <?=$_SESSION['flash_msg']?>
  <?php $_SESSION['flash_flag'] = false; ?>
</div>
<?php endif; ?>
<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $new_nickname = ($_POST['new-nickname']==="") ? null : $_POST['new-nickname'];
    $duplicate_flg = false;
    if ($new_nickname !== null) {
      $stmt = $dbh->prepare("SELECT COUNT(*) FROM users WHERE nickname = ?");
      $stmt->execute([$new_nickname]);
      $duplicateCount = $stmt->fetchColumn();
      if ($duplicateCount > 0) {  // 変更しようとする表示名が重複している場合
        $duplicate_flg = true;
      }
    }
    $stmt = $dbh->prepare("UPDATE users SET nickname = ? WHERE user_id = ?");
    $stmt->execute([$new_nickname, $_SESSION['user_id']]);
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "表示名を変更しました．";
    $_SESSION['flash_flag'] = true;
  } catch (PDOException $e) {
    if ($duplicate_flg) {
      $_SESSION['status'] = "danger";
      $_SESSION['flash_msg'] = "その表示名は既に使用されています．";
      $_SESSION['flash_flag'] = true;
    } else {
      $_SESSION['status'] = "danger";
      $_SESSION['flash_msg'] = "表示名の変更に失敗しました．";
      $_SESSION['flash_flag'] = true;
    }
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
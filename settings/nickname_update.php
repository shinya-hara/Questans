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
    $stmt = $dbh->prepare("UPDATE users SET nickname = ? WHERE user_id = ?");
    $stmt->execute([$new_nickname, $_SESSION['user_id']]);
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "ニックネーム（表示名）を変更しました．";
    $_SESSION['flash_flag'] = true;
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "ニックネーム（表示名）の変更に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}

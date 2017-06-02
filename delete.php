<?php
require_once __DIR__.'/db_info.php';
require_once __DIR__.'/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("delete from questionnaires where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    // 削除に失敗: 0, 成功: 1,
    if ($rowCount > 0) {
      echo '<div class="text-success">削除に成功しました．</div>';
    } else {
      echo '<div class="text-danger">削除に失敗しました．</div>';
    }
  } catch (PDOException $e) {
    // $_SESSION['status'] = "danger";
    // $_SESSION['flash_msg'] = "削除に失敗しました．";
    // $_SESSION['flash_flag'] = true;
    echo '<div class="text-danger">削除に失敗しました．</div>';
  }
} catch (PDOException $e) {
  // $_SESSION['status'] = "danger";
  // $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  // $_SESSION['flash_flag'] = true;
  echo '<div class="text-danger">データベースの接続に失敗しました．</div>';
}
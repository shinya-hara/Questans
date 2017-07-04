<?php
require_once __DIR__.'/db_info.php';
require_once __DIR__.'/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケートタイトルの取得
    $stmt = $dbh->prepare("SELECT title FROM questionnaires WHERE q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $title = $stmt->fetch();
    // アンケートタイトルに削除済みを表す文字列を付加
    date_default_timezone_set('Asia/Tokyo');  // タイムゾーンの設定
    $deleted_title = 'deleted_at_'.date("Y-m-d_H:i:s", time()).'__'.$title['title'];
    // 削除フラグを立てる
    // $stmt = $dbh->prepare("delete from questionnaires where q_id = ?");
    $stmt = $dbh->prepare("UPDATE questionnaires SET isDeleted = true, title = ? WHERE q_id = ?");
    $stmt->bindValue(1, $deleted_title);
    $stmt->bindValue(2, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    // 削除に失敗: 0, 成功: 1,
    if ($rowCount > 0) {
      echo '<div class="text-success">削除に成功しました．</div>';
    } else {
      echo '<div class="text-danger">削除に失敗しました．</div>';
    }
  } catch (PDOException $e) {
    echo '<div class="text-danger">削除に失敗しました．</div>';
  }
} catch (PDOException $e) {
  echo '<div class="text-danger">データベースの接続に失敗しました．</div>';
}
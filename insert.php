<?php
require_once __DIR__.'/functions.php';
session_start();

// DB接続のための情報を読み込む
require_once __DIR__.'/db_info.php';

// フラッシュメッセージを表示する為のフラグ
$_SESSION['flash_flag'] = true;

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $dbh->beginTransaction();   // トランザクションの開始
    // アンケートをDBに格納
    $stmt = $dbh->prepare("insert into questionnaires (title, created, owner, isPrivate) values (?, ?, ?, ?)");
    $stmt->bindValue(1, $_SESSION['title']);
    date_default_timezone_set('Asia/Tokyo');  // タイムゾーンの設定
    $stmt->bindValue(2, date("Y-m-d H:i:s", time()));   // 現在時刻を取得
    $stmt->bindValue(3, $_SESSION['user_id']);
    $stmt->bindValue(4, (int)$_SESSION['isPrivate'], PDO::PARAM_INT);
    $stmt->execute();
    $id = $dbh->lastInsertId();   // 最後に追加したレコードのID
    
    // 各質問をDBに格納
    $stmt = $dbh->prepare("insert into questions (q_id, q_num, question) values (?, ?, ?)");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    for ($i = 1, $q_size = $_SESSION['q_num']; $i <= $q_size; $i++) {
      $stmt->bindValue(2, $i, PDO::PARAM_INT);
      $stmt->bindValue(3, $_SESSION['q'.$i]);
      $stmt->execute();
    }
    
    // 選択肢をDBに格納
    $stmt = $dbh->prepare("insert into choices (q_id, c_num, choice) values (?, ?, ?)");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    for ($i = 1, $c_size = $_SESSION['c_num']; $i <= $c_size; $i++) {
      $stmt->bindValue(2, $i, PDO::PARAM_INT);
      $stmt->bindValue(3, $_SESSION['c'.$i]);
      $stmt->execute();
    }
    
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "アンケートの作成に成功しました．";
    $dbh->commit();
  } catch (PDOException $e) {
    $dbh->rollBack();
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "アンケートの作成に失敗しました．";
    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['flash_msg'].'</div>';
  }
} catch (PDOException $e) {
  // echo $e->getMessage();
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['flash_msg'].'</div>';
}


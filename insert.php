<?php
session_start();
// エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// 選択肢の設定
$choice = [ '全くそう思わない',
            'あまりそう思わない',
            'どちらとも言えない',
            'ややそう思う',
            '非常にそう思う' ];

// 選択肢のサイズ
$c_size = count($choice);

// DB接続のための諸情報
$dsn = 'mysql:dbname=questionnarie;host=localhost;charset=utf8';
$user = 'dbuser';
$password = 'dbpass';

// フラッシュメッセージを表示する為のフラグ
$_SESSION['flash_flag'] = true;

try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $dbh->beginTransaction();   // トランザクションの開始
    // アンケートをDBに格納
    $stmt = $dbh->prepare("insert into questionnaries (title, created) values (?, ?)");
    $stmt->bindValue(1, $_SESSION['title']);
    date_default_timezone_set('Asia/Tokyo');  // タイムゾーンの設定
    $stmt->bindValue(2, date("Y-m-d H:i:s", time()));   // 現在時刻を取得
    $stmt->execute();
    $id = $dbh->lastInsertId();   // 最後に追加したレコードのID
    
    // 各質問をDBに格納
    $stmt = $dbh->prepare("insert into questions (q_id, q_num, question) values (?, ?, ?)");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    for ($i = 1, $q_size = $_SESSION['num']; $i <= $q_size; $i++) {
      $stmt->bindValue(2, $i, PDO::PARAM_INT);
      $stmt->bindValue(3, $_SESSION['q'.$i]);
      $stmt->execute();
    }
    
    // 選択肢をDBに格納
    $stmt = $dbh->prepare("insert into choices (q_id, c_num, choice) values (?, ?, ?)");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    for ($i = 1; $i <= $c_size; $i++) {
      $stmt->bindValue(2, $i, PDO::PARAM_INT);
      $stmt->bindValue(3, $choice[$i-1]);
      $stmt->execute();
    }
    
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "アンケートの作成に成功しました．";
    $dbh->commit();
    echo "";
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


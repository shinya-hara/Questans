<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                  PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $dbh->beginTransaction();   // トランザクションの開始
    // 回答情報の格納
    $stmt = $dbh->prepare("insert into answers (q_id,user_id,answered) values (?, ?, ?)");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->bindValue(2, $_SESSION['user_id']);
    date_default_timezone_set('Asia/Tokyo');  // タイムゾーンの設定
    $stmt->bindValue(3, date("Y-m-d H:i:s", time()));   // 現在時刻を取得
    $stmt->execute();
    $id = $dbh->lastInsertId();   // 最後に追加したレコードのID
    
    // 回答詳細の格納
    $stmt = $dbh->prepare("insert into ans_detail (ans_id,q_num,answer) values (?, ?, ?)");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    for ($i = 1; $i <= $_POST['q_cnt']; $i++) {
      $stmt->bindValue(2, $i, PDO::PARAM_INT);
      $stmt->bindValue(3, $_POST['a'.$i], PDO::PARAM_INT);
      $stmt->execute();
    }
    $dbh->commit();
    
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "回答結果を送信しました．";
    $_SESSION['flash_flag'] = true;
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "回答結果の送信に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>送信完了 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/header.php'; ?>
    <div class="container">
      <?php include __DIR__.'/flash.php'; ?>
      <?php
      for ($i = 1; $i <= $_POST['q_cnt']; $i++) {
        echo $i.'番の回答は'.$_POST['a'.$i].'<br>';
      }
      ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
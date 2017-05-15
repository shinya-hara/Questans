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
$password = 'dqwt12';

try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
  // echo $e->getMessage();
  echo "データベース接続エラー";
  die();
}
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
} catch (PDOException $e) {
  $dbh->rollBack();
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "アンケートの作成に失敗しました．";
}
// フラッシュメッセージを表示する為のフラグ
$_SESSION['flash_flag'] = true;
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>作成完了 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>作成完了</h2>
      <div class="alert alert-<?=$_SESSION['status']?>" role="alert">
        <?=$_SESSION['flash_msg']?>
        <!--<a href="questionnarie.php" target="_blank"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> アンケート画面へ</a>-->
      </div>
      <a href="make.php"><input type="button" value="アンケート作成画面へ" class="btn btn-primary"></a>
      <hr>
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="text-center">番号</th><th>質問</th>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
          <tr>
            <td class="text-center"><?= $i ?></td>
            <td><?= h($_SESSION['q'.$i]) ?></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <?php require('debug.php'); ?>
    </div>
  </body>
</html>
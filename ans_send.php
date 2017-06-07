<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $dbh = new PDO($dsn, $user, $password,
                   [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_EMULATE_PREPARES => false ]);
    try {
      // アンケート情報の取得
      $stmt = $dbh->prepare("select title,created,updated,owner,user_name from questionnaires,users where q_id = ? && owner = user_id");
      $stmt->bindValue(1, (int)$_SESSION['q_id'], PDO::PARAM_INT);
      $stmt->execute();
      $questionnaires = $stmt->fetch();
      // アンケートの回答数をカウント
      $stmt = $dbh->prepare("select count(*) from answers where q_id = ?");
      $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
      $stmt->execute();
      $answeredCount = $stmt->fetchColumn();
      $dbh->beginTransaction();   // トランザクションの開始
      // 同じユーザが同じアンケートに回答済みでないかチェックする
      $stmt = $dbh->prepare("select count(*) from answers where q_id = ? and user_id = ? limit 1");
      $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
      $stmt->bindValue(2, (int)$_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->execute();
      $rowCount = $stmt->fetchColumn();
      if ($rowCount == 0) {
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
        $_SESSION['flash_msg'] = "ありがとうございます.<br>回答結果を送信しました．";
        $_SESSION['flash_flag'] = true;
      } else {
        $_SESSION['status'] = "danger";
        $_SESSION['flash_msg'] = "あなたは既にこのアンケートに回答済みです．";
        $_SESSION['flash_flag'] = true;
      }
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
} else {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "不正なアクセスです．";
  $_SESSION['flash_flag'] = true;
}
?>
<?php include __DIR__.'/flash.php'; ?>
<div class="container">
  <a href="management.php" class="btn btn-default">マイページ</a>
</div>
<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
if (isset($_POST['q_id'])) { $_SESSION['q_id'] = $_POST['q_id']; }
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // 質問を取得
    $stmt = $dbh->prepare("select * from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, $_SESSION['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    $qCount = count($questions);  // 質問数
    // 選択肢を取得
    $stmt = $dbh->prepare("select * from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, $_SESSION['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
    $cCount = count($choices);  // 選択肢数
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "アンケート一覧の取得に失敗しました．";
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
    <title>回答 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/header.php'; ?>
    <div class="container">
      <h2>アンケート回答</h2>
      <?php include __DIR__.'/flash.php'; ?>
      <table class="table text-center">
        <thead>
          <tr>
            <th class="text-center td-num">番号</th>
            <th>質問</th>
            <?php for ($i = 0; $i < $cCount; $i++): ?>
            <th class="text-center"><?=h($choices[$i]['choice'])?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < $qCount; $i++): ?>
          <tr>
            <td class="td-num"><?=$i+1?></td>
            <td class="text-left"><?=h($questions[$i]['question'])?></td>
            <?php for ($j = 0; $j < $cCount; $j++): ?>
            <td><input type="radio" name="q<?=$i+1?>"></td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
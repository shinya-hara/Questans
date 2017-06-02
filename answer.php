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
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created,updated,owner,user_name from questionnaires,users where q_id = ? && owner = user_id");
    $stmt->bindValue(1, (int)$_SESSION['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetch();
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
    // アンケートの回答数をカウント
    $stmt = $dbh->prepare("select count(*) from answers where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answeredCount = $stmt->fetchColumn();
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
    <?php include __DIR__.'/contentHeader.php'; ?>
    <div class="container">
      <form method="post" action="answered.php" role="form" data-toggle="validator">
        <table class="table text-center">
          <thead>
            <tr>
              <th class="text-center td-num none">番号</th>
              <th>質問</th>
              <?php for ($i = 0; $i < $cCount; $i++): ?>
              <th class="text-center"><?=h($choices[$i]['choice'])?></th>
              <?php endfor; ?>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 0; $i < $qCount; $i++): ?>
            <tr>
              <td class="td-num none"><?=$i+1?></td>
              <td class="text-left"><?=h($questions[$i]['question'])?></td>
              <?php for ($j = 0; $j < $cCount; $j++): ?>
              <td><input type="radio" name="a<?=$i+1?>" value="<?=$choices[$j]['c_num']?>" required></td>
              <?php endfor; ?>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
        <input type="hidden" name="q_id" value="<?=$_SESSION['q_id']?>">
        <input type="hidden" name="q_cnt" value="<?=$qCount?>">
        <input type="submit" class="btn btn-primary btn-block" value="回答">
        <!--<button type="button" class="btn btn-primary btn-block">回答</button>-->
      </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script>
      $(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('#headerAnswerBtn').hide();
      });
    </script>
  </body>
</html>
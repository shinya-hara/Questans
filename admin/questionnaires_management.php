<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_admin_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->query("SELECT * FROM questionnaires ORDER BY created DESC");
    $questionnaires = $stmt->fetchAll();
    $user_info = $dbh->query("select user_id,user_name,nickname from users");
    // key: ユーザID, value: { ユーザ名, 表示名 } となる連想配列の連想配列を作る
    while ($row = $user_info -> fetch()) {
      $tmp['user_name'] = $row['user_name'];
      $tmp['nickname'] = $row['nickname'];
      $users[$row['user_id']] = $tmp;
    }
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "タイトルの取得に失敗しました．";
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
    <link rel="stylesheet" href="../css/styles.css">
    <title>アンケート管理 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/../header.php'; ?>
    <?php include __DIR__.'/../guest_alert.php'; ?>
    <?php include __DIR__.'/../flash.php'; ?>
    <div class="container">
      <ul class="nav nav-tabs">
        <li role="presentation"><a href="/admin/users_management.php">ユーザ一覧</a></li>
        <li role="presentation" class="active"><a href="">アンケート一覧</a></li>
      </ul>
    </div>
    <main>
      <div class="container">
        <table class="table table-hover">
          <thead>
            <th class="none text-center td-num">番号</th>
            <th>タイトル</th>
            <th>ユーザ名</th>
            <th>表示名</th>
            <th class="none">作成日時</th>
            <th class="none">更新日時</th>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($questionnaires as $questionnaire): ?>
            <tr class="show-detail" data-id="<?=$questionnaire['q_id']?>">
              <td class="none text-center td-num"><?=$i?></td>
              <td><?=$questionnaire['isPrivate']?'<span class="glyphicon glyphicon-lock" aria-hidden="true"></span> ':''?><?=h($questionnaire['title'])?></td>
              <td><?=h($users[$questionnaire['owner']]['user_name'])?></td>
              <td><?=$users[$questionnaire['owner']]['nickname']!==null?h($users[$questionnaire['owner']]['nickname']):"---"?></td>
              <td class="none"><?=substr(h($questionnaire['created']),0,16)?></td>
              <td class="none"><?=is_null($questionnaire['updated'])?"---":substr(h($questionnaire['updated']),0,16)?></td>
            </tr>
            <?php $i++; endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
    <?php include __DIR__.'/../footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="https://use.fontawesome.com/5bf7a4a25c.js"></script>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
    $(function(){
      // 詳細
      $('tbody tr').on('click', function() {
        $.post('questionnaire_detail.php',
        {
          'q_id': $(this).attr('data-id')
        },
        function(data) {
          $('main').html(data);
        });
      });
    });
    </script>
  </body>
</html>
<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_logined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->query("SELECT * FROM users WHERE role = 2 ORDER BY user_id");
    $users = $stmt->fetchAll();
    // 各ユーザの作成したアンケート数
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM questionnaires WHERE owner = ?");
    foreach ($users as $user) {
      $stmt->execute([$user['user_id']]);
      $count[$user['user_id']]['questionnaires'] = $stmt->fetchColumn();
    }
    // 各ユーザのアンケート回答数
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM answers WHERE user_id = ?");
    foreach ($users as $user) {
      $stmt->execute([$user['user_id']]);
      $count[$user['user_id']]['answers'] = $stmt->fetchColumn();
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
    <title>ユーザ管理 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/../header.php'; ?>
    <?php include __DIR__.'/../guest_alert.php'; ?>
    <div class="container">
      <?php include __DIR__.'/../flash.php'; ?>
      <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">ユーザ一覧</a></li>
        <li role="presentation"><a href="#">アンケート一覧</a></li>
      </ul>
      
      <table class="table table-hover">
        <thead>
          <th class="none text-center td-num">番号</th>
          <th>ユーザ名</th>
          <th>表示名</th>
          <th>アンケート数</th>
          <th>回答数</th>
        </thead>
        <tbody>
          <?php $i = 1; foreach ($users as $user): ?>
          <tr data-id="<?=h($user['user_id'])?>">
            <td class="none text-center td-num"><?=$i?></td>
            <td><?=h($user['user_name'])?></td>
            <td><?=($user['nickname'] !== null) ? h($user['nickname']) : "---"?></td>
            <td><?=$count[$user['user_id']]['questionnaires']?></td>
            <td><?=$count[$user['user_id']]['answers']?></td>
          </tr>
          <?php $i++; endforeach; ?>
        </tbody>
      </table>
    </div><!-- /container -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/5bf7a4a25c.js"></script>
    <script>
    $(function(){
      $('#nickname-update').on('click', function() {
        var $btn = $(this).button('loading');
        $.post('nickname_update.php',
        {
          'new-nickname': $('#new-nickname').val()
        },
        function(data) {
          if (data === "") {
            window.location.href = "/management.php";
          } else {
            $btn.button('reset');
            $('#msg').html(data);
          }
        });
      });
    });
    </script>
  </body>
</html>
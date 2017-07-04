<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/db_info.php';
require_logined_session();
$_SESSION['update'] = 0;    // アンケートの編集を表すフラグ
$_SESSION['from'] = "list"; // 遷移元を表す変数
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $questionnaires = $dbh->query("select * from questionnaires where isPrivate is false order by created desc");
    $stmt = $dbh->query("select count(*) from questionnaires");
    $rowCount = $stmt->fetchColumn();   // 公開アンケート数
    $owners = $dbh->query("select user_id,user_name,nickname from users,questionnaires where owner = user_id");
    // キーがユーザID、値がユーザ名の連想配列を作る
    while ($row = $owners -> fetch()) {
      $tmp['user_name'] = $row['user_name'];
      $tmp['nickname'] = $row['nickname'];
      $users[$row['user_id']] = $tmp;
    }
    // 各アンケートに回答済みかチェック
    $stmt = $dbh->prepare("select * from answers where user_id = ?");
    $stmt->bindValue(1, (int)$_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answered = $stmt->fetchAll();
    foreach ($answered as $row) {
      $answered_flg[$row['q_id']] = true;
    }
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
<?php include __DIR__.'/flash.php'; ?>
<div class="container">
  <h2>公開アンケート一覧</h2>
  <?php if ($rowCount > 0):
    include __DIR__.'/list_table.php'; ?>
  <?php elseif ($_SESSION['username'] == 'guest'): ?>
    <h3>まだ公開されているアンケートがありません．<br>ゲストユーザ以外でログインし，最初のアンケートを作成しましょう！</h3>
    <a href="make.php"><button class="btn btn-primary btn-lg" disabled><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a><br><br>
  <?php else: ?>
    <h3>まだ公開されているアンケートがありません．<br>最初のアンケートを作成しましょう！</h3>
    <a href="make.php"><button class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a><br><br>
  <?php endif; ?>
  <button type="button" class="btn btn-default" id="mypage">マイページ</button>
</div>
<script>
  $(function() {
    // 詳細
    $('tr td:not(.owner)').on('click', function() {
      $.post('detail.php',
      {
        'q_id': $(this).parent().attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    // マイページ
    $('#mypage').on('click', function() {
      $.post('user.php',
      {
        'req_user_id': <?=$_SESSION['user_id']?>
      },
      function(data) {
        $('main').html(data);
      });
    });
    // ユーザページ
    $('tr td.owner').on('click', function() {
      $.post('user.php',
      {
        'req_user_id': $(this).attr('data-userid')
      },
      function(data) {
        $('main').html(data);
      });
    });
  });
</script>
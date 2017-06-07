<?php
require_once __DIR__.'/db_info.php';
require_once __DIR__.'/functions.php';
require_logined_session();
$_SESSION['update'] = 0;        // アンケートの編集を表すフラグ
$_SESSION['from'] = "userpage"; // 遷移元を表す変数
$_SESSION['prev_req_user_id'] = $_POST['req_user_id']; // 呼び出されたユーザID
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("select q_id,title,created,updated,owner from questionnaires where owner = ? order by created desc");
    $stmt->bindValue(1, (int)$_POST['req_user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetchAll();
    $rowCount = count($questionnaires);   // ユーザの作成したアンケート数
    $owners = $dbh->query("select user_id,user_name from users,questionnaires where owner = user_id");
    // キーがユーザID、値がユーザ名の連想配列を作る
    $users[$_SESSION['user_id']] = $_SESSION['username'];
    while ($row = $owners -> fetch()) {
      $users[$row['user_id']] = $row['user_name'];
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
  <h2><?=$users[$_POST['req_user_id']]?></h2>
  <?php if ($rowCount > 0):
    include __DIR__.'/list_table.php'; ?>
  <?php elseif ($_SESSION['username'] == 'guest'): ?>
    <h3>ゲストユーザでアンケートは作成できません．<br>アンケート内容の確認のみが行えます．</h3>
    <a href="make.php"><button class="btn btn-primary btn-lg" disabled><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a><br><br>
  <?php else: ?>
    <h3>まだアンケートがありません．<br>最初のアンケートを作成しましょう！</h3>
    <a href="make.php"><button class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a><br><br>
  <?php endif; ?>
  <button type="button" class="btn btn-default" id="list">公開アンケート一覧</button>
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
    // 全ユーザのアンケート一覧
    $('#list').on('click', function() {
      $.get('list.php', function(data) {
        $('main').html(data);
      });
    });
  });
</script>
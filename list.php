<?php
ini_set( 'display_errors', 1 );
require_once __DIR__.'/functions.php';
require_once __DIR__.'/db_info.php';
require_logined_session();

$_SESSION['from'] = "list"; // 遷移元を表す変数
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $questionnaires = $dbh->query("select q_id,title,created,updated,owner from questionnaires order by created");
    $stmt = $dbh->query("select count(*) from questionnaires");
    $rowCount = $stmt->fetchColumn();   // 公開アンケート数
    $owners = $dbh->query("select user_id,user_name from users,questionnaires where owner = user_id");
    // キーがユーザID、値がユーザ名の連想配列を作る
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
<h2>公開アンケート一覧</h2>
<?php if ($rowCount > 0):
  include __DIR__.'/list_table.php'; ?>
<?php else: ?>
  <h3>まだ公開されているアンケートがありません．<br>最初のアンケートを作成しましょう！</h3>
  <a href="make.php"><button class="btn btn-primary btn-lg" <?=$_SESSION['username']=='guest'?'disabled':''?>><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a><br><br>
<?php endif; ?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
  $(function() {
    // 詳細
    $('tr td:not(.owner)').on('click', function() {
      $.post('detail.php',
      {
        'id': $(this).parent().attr('data-id')
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
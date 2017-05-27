<?php
require_once __DIR__.'/db_info.php';
require_once __DIR__.'/functions.php';
require_logined_session();
$_SESSION['from'] = "userpage"; // 遷移元を表す変数
$_SESSION['prev_req_user_id'] = $_POST['req_user_id']; // 呼び出されたユーザID
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("select q_id,title,created,updated,owner from questionnaires where owner = ? order by created");
    $stmt->bindValue(1, (int)$_POST['req_user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetchAll();
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
<h2><?=$users[$_POST['req_user_id']]?></h2>
<button type="button" class="btn btn-default" id="list">公開アンケート一覧</button>
<?php include __DIR__.'/list_table.php'; ?>
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
    // 全ユーザのアンケート一覧
    $('#list').on('click', function() {
      $.get('list.php', function(data) {
        $('main').html(data);
      });
    });
  });
</script>
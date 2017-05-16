<?php
session_start();
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $stmt = $dbh->prepare("select * from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "アンケート一覧の取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  echo 'DB接続エラーっすわ';
}
?>
<h3>詳細確認</h3>
<p>詳細を確認できます</p>
<button type="button" class="btn btn-primary" id="back">Back</button>
<ol>
<?php foreach($questions as $row): ?>
<li><?=$row['question']?></li>
<?php endforeach; ?>
</ol>
<script>
  $('#back').on('click', function() {
    $.get('list.php', function(data) {
      $('main').html(data);
    });
  });
</script>
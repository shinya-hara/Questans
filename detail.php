<?php
session_start();
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created from questionnaries where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaries = $stmt->fetch(PDO::FETCH_ASSOC);
    // 質問の取得
    $stmt = $dbh->prepare("select q_num,question from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    // 選択肢の取得
    $stmt = $dbh->prepare("select c_num,choice from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "詳細の取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<?php include __DIR__.'/flash.php'; ?>
<button type="button" class="btn btn-primary" id="back">BACK</button>
<h3><?=$questionnaries['title']?><small><br>Created at <?=$questionnaries['created']?></small></h3>
<!-- 質問 -->
<table class="table">
  <thead>
    <th>番号</th><th>質問</th>
  </thead>
  <tbody>
    <?php foreach($questions as $row): ?>
    <tr><td><?=$row['q_num']?></td><td><?=$row['question']?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>
<!-- 選択肢 -->
<table class="table">
  <thead>
    <th>番号</th><th>選択肢</th>
  </thead>
  <tbody>
    <?php foreach($choices as $row): ?>
    <tr><td><?=$row['c_num']?></td><td><?=$row['choice']?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Button trigger modal -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal">
  DELETE
</button>

<!-- Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delModalLabel">アンケートの削除</h4>
      </div>
      <div class="modal-body">
        このアンケートを削除しますか？<br>
        この操作は取り消せません．
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="delete">Delete</button>
      </div>
    </div>
  </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
  $(function() {
    $('#back').on('click', function() {
      $.get('list.php', function(data) {
        $('main').html(data);
      });
    });
    // 削除
    $('#delete').on('click', function() {
      alert("deleted");
    });
  });
</script>
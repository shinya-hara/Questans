<?php
session_start();
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $questionnaries = $dbh->query("select q_id,title,created from questionnaries order by q_id");
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
<h3>アンケート一覧</h3>
<table class="table table-hover" id="list">
  <thead>
    <th>番号</th><th>タイトル</th><th>作成日時</th><th>操作</th>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($questionnaries as $row): ?>
    <tr data-id="<?=$row['q_id']?>">
      <td><?=$i?></td>
      <td><?=$row['title']?></td>
      <td><?=$row['created']?></td>
      <td>
        <!--<div class="btn-group btn-group-sm" role="group">-->
          <button type="button" class="btn btn-info info" data-id="<?=$row['q_id']?>">詳細</button>
          <button type="button" class="btn btn-primary edit" data-id="<?=$row['q_id']?>">編集</button>
          <button type="button" class="btn btn-danger delete" data-id="<?=$row['q_id']?>">削除</button>
        <!--</div>-->
      </td>
    </tr>
    <?php $i++; endforeach; ?>
  </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
  $(function() {
    // 詳細
    $('.info, tr').on('click', function() {
      $.post('detail.php',
      {
        'id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
  });
</script>
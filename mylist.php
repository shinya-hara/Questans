<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/db_info.php';
require_logined_session();

$_SESSION['from'] = "mylist"; // 遷移元を表す変数
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("select q_id,title,created,updated from questionnaries where owner = ? order by created");
    $stmt->bindValue(1, (int)$_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaries = $stmt->fetchAll();
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
<button type="button" class="btn btn-default" id="all-list">全ユーザのアンケート一覧</button>
<h3><?=h($_SESSION['username'])?>のアンケート一覧</h3>
<table class="table table-hover" id="list">
  <thead>
    <th>番号</th>
    <th>タイトル</th>
    <th>作成日時</th>
    <th>更新日時</th>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($questionnaries as $row): ?>
    <tr data-id="<?=h($row['q_id'])?>">
      <td><?=$i?></td>
      <td><?=h($row['title'])?></td>
      <td><?=h($row['created'])?></td>
      <td><?=is_null($row['updated'])?"---":h($row['updated'])?></td>
    </tr>
    <?php $i++; endforeach; ?>
  </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
    // 全ユーザのアンケート一覧
    $('#all-list').on('click', function() {
      $.get('list.php', function(data) {
        $('main').html(data);
      });
    });
  });
</script>
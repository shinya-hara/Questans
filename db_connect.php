<?php
session_start();
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    // $stmt = $dbh->prepare("select * from questionnaries");
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
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>管理画面 | アンケートシステム</title>
    <style>
      .table tbody>tr>td {
        vertical-align: middle;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>管理画面</h2>
      <?php if (isset($_SESSION['flash_flag']) && $_SESSION['flash_flag']): ?>
      <div class="alert alert-<?=$_SESSION['status']?>" role="alert">
        <?=$_SESSION['flash_msg']?>
        <?php $_SESSION['flash_flag'] = false; ?>
      </div>
      <?php endif; ?>
      <a href="make.php"><button type="button" class="btn btn-default">アンケート作成画面</button></a>
      <h3>アンケート一覧</h3>
      <table class="table">
        <thead>
          <th>番号</th><th>タイトル</th><th>作成日時</th><th>操作</th>
        </thead>
        <tbody>
          <?php $i = 1; foreach ($questionnaries as $row): ?>
          <tr>
            <td><?=$i?></td>
            <td><?=$row['title']?></td>
            <td><?=$row['created']?></td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-info info" id="info_<?=$row['q_id']?>" name="info_<?=$row['q_id']?>" data-id="<?=$row['q_id']?>">詳細</button>
                <button type="button" class="btn btn-primary edit" id="edit_<?=$row['q_id']?>" name="edit_<?=$row['q_id']?>" data-id="<?=$row['q_id']?>">編集</button>
                <button type="button" class="btn btn-danger delete" id="delete_<?=$row['q_id']?>" name="delete_<?=$row['q_id']?>" data-id="<?=$row['q_id']?>">削除</button>
              </div>
            </td>
          </tr>
          <?php $i++; endforeach; ?>
        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
      $('.info').on('click', function() {
        console.log($(this).attr('data-id'));
        $.post('make.php',
        {
          'id': $(this).attr('data-id')
        },
        function(data) {
          $('body').html(data);
        });
      });
      
    </script>
  </body>
</html>
<?php
$_SESSION = array();
?>
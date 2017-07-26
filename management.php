<?php
require_once __DIR__.'/functions.php';
require_logined_session();
// 管理者権限でログインしている場合、管理者画面に遷移
if ($_SESSION['role']==1) {
  header('Location: /admin/users_management.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>管理画面 | アンケートシステム</title>
  </head>
  <body>
    <?php include __DIR__.'/header.php'; ?>
    <?php include __DIR__.'/guest_alert.php'; ?>
    <main>
      <div class="container">
        読み込み中...
      </div>
    </main>
    <?php include __DIR__.'/debug.php'; ?>
    <?php include __DIR__.'/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
    $(function() {
      $('.dropdown-toggle').dropdown();
      
      <?php if (isset($_GET['q_id']) && is_numeric($_GET['q_id'])): ?>
      $.post('detail.php',
      {
        'q_id': <?=$_GET['q_id']?>
      },
      function(data) {
        $('main').html(data);
      });
      <?php else: ?>
      $.post('user.php',
      {
        'req_user_id': <?=$_SESSION['user_id']?>
      },
      function(data) {
        $('main').html(data);
      });
      <?php endif; ?>
      
      $('#mypage').on('click', function() {
        $.post('user.php',
        {
          'req_user_id': <?=$_SESSION['user_id']?>
        },
        function(data) {
          $('main').html(data);
        });
      });
    });
    </script>
  </body>
</html>
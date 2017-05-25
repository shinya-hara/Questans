<?php
require_once __DIR__.'/functions.php';
require_logined_session();
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
    #list tbody>tr {
      cursor: pointer;
    }
    .table tbody>tr>td {
      vertical-align: middle;
    }
    .modal-backdrop.in {
    /*filter: alpha(opacity=50);*/
      opacity: 0.3;
    }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1>
      <?php if ($_SESSION['username'] == 'guest'): ?>
      <div class="alert alert-warning">
        ゲストユーザでログインしています.<br>
        アンケートの回答結果は送信されますが，個人の回答は管理できません.
        ゲストユーザではアンケートを作成できません.
      </div>
      <?php endif; ?>
      <a href="/logout.php?token=<?=h(generate_token())?>" class="btn btn-default">ログアウト</a>
      <hr>
      <h2>管理画面</h2>
      <a href="make.php"><button class="btn btn-default" <?=$_SESSION['username']=='guest'?'disabled':''?>>アンケート作成画面</button></a>
      <main>
        <?php include 'mylist.php'; ?>
      </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
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
    </style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1>
      <?php if ($_SESSION['username'] == 'guest'): ?>
      <div class="alert alert-warning">
        ゲストアカウントでログインしています.
        アンケートの回答結果は送信されますが個人の回答は管理できません.
      </div>
      <?php endif; ?>
      <a href="/logout.php?token=<?=h(generate_token())?>" class="btn btn-default">ログアウト</a>
      <hr>
      <h2>管理画面</h2>
      <a href="make.php" class="btn btn-default">アンケート作成画面</a>
      <main>
        <?php include 'list.php'; ?>
      </main>
    </div>
  </body>
</html>
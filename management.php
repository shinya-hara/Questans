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
      <h1>アンケートシステム</h1><hr>
      <h2>管理画面</h2>
      <a href="make.php"><button type="button" class="btn btn-default">アンケート作成画面</button></a>
      <main>
        <?php include 'list.php'; ?>
      </main>
    </div>
  </body>
</html>
<?php
$_SESSION = array();
?>
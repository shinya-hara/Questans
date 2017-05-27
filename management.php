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
    .table tbody>tr>td.owner, span.owner {
      color: #337ab7;
      cursor: pointer;
    }
    .table tbody>tr>td.owner:hover, .owner:hover {
      color: #23527c;
      text-decoration: underline;
    }
    .modal-backdrop.in {
      opacity: 0.3;
    }
    /*.jumbotron {*/
    /*  background-color: #428bca;*/
    /*  color: #fff;*/
    /*}*/
    </style>
  </head>
  <body>
    <!--<header class="jumbotron">-->
      <div class="container">
        <div class="clearfix">
          <h1 class="pull-left">アンケートシステム</h1>
          <button type="button" class="btn btn-default pull-right" id="mypage"><?=h($_SESSION['username'])?></button>
          <a href="/logout.php?token=<?=h(generate_token())?>" class="btn btn-default pull-right">ログアウト</a>
          <a href="make.php"><button class="btn btn-default pull-right" <?=$_SESSION['username']=='guest'?'disabled':''?>>アンケート作成画面</button></a>
        </div>
      </div>
    <!--</header>-->
    <div class="container">
      <?php if ($_SESSION['username'] == 'guest'): ?>
      <div class="alert alert-warning">
        ゲストユーザでログインしています.<br>
        アンケートの回答結果は送信されますが，個人の回答は管理できません.
        ゲストユーザではアンケートを作成できません.
      </div>
      <?php endif; ?>
      <hr>
      <main></main>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
    $(function() {
      var post = $.post('user.php',
      {
        'req_user_id': <?=$_SESSION['user_id']?>
      },
      function(data) {
        $('main').html(data);
      });
      $('#mypage').on('click', function() {
        post;
      });
    });
    </script>
  </body>
</html>
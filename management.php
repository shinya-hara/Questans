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
    /* header */
    header {
      margin-bottom: 10px;
      border-bottom: 1px solid rgba(43,91,6,.15);
    }
    header h1 {
      line-height: 48px;
      font-size: 24px;
      margin: 0;
      padding: 0;
    }
    header>div.container>div.buttons {
      margin: 7px 0;
    }
    header>div.container>div.buttons button {
      margin-left: 10px;
    }
    
    .table tbody>tr>td {
      vertical-align: middle;
      cursor: pointer;
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
    .dropdown-menu>li>a.link {
      color: #337ab7;
      cursor: pointer;
    }
    .dropdown-menu>li>a.link:hover {
      color: #23527c;
      text-decoration: underline;
    }
    </style>
  </head>
  <body>
    <header>
      <div class="container clearfix">
        <h1 class="pull-left">アンケートシステム</h1>
        <div class="buttons">
          <div class="dropdown pull-right">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <?=$_SESSION['username']?>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a id="mypage" class="link"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> マイページ</a></li>
              <li role="separator" class="divider"></li>
              <li><a class="link" href="/logout.php?token=<?=h(generate_token())?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト</a></li>
            </ul>
          </div>
          <a href="make.php"><button class="btn btn-primary pull-right" <?=$_SESSION['username']=='guest'?'disabled':''?>><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a>
        </div>
      </div>
    </header>
    <div class="container">
      
      <?php if ($_SESSION['username'] == 'guest'): ?>
      <div class="alert alert-warning">
        ゲストユーザでログインしています.<br>
        アンケートの回答結果は送信されますが，個人の回答は管理できません.
        ゲストユーザではアンケートを作成できません.
      </div>
      <?php endif; ?>
      <main>読み込み中...</main>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
    $(function() {
      $('.dropdown-toggle').dropdown();
      $.post('user.php',
      {
        'req_user_id': <?=$_SESSION['user_id']?>
      },
      function(data) {
        $('main').html(data);
      });
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
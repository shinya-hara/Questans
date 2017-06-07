<header>
  <div class="container clearfix">
    <h1 class="pull-left"><a href="management.php">アンケートシステム</a></h1>
    <div class="buttons">
      <div class="dropdown pull-right">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <?=$_SESSION['username']?>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
          <li><a href="management.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> マイページ</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="/logout.php?token=<?=h(generate_token())?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト</a></li>
        </ul>
      </div>
      <a href="make.php"><button id="make-questionnaire" class="btn btn-primary pull-right" <?=$_SESSION['username']=='guest'?'disabled':''?>><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a>
    </div>
  </div>
</header>
<header>
  <div class="container clearfix">
    <h1 class="pull-left"><a href="/management.php">アンケートシステム</a></h1>
    <div class="buttons">
      <div class="btn-group pull-right">
        <?php if ($_SESSION['role']==1): ?>
        <a href="/admin/users_management.php" class="btn btn-default"><?=h($_SESSION['username'])?></a>
        <?php else: ?>
        <a href="/management.php" class="btn btn-default"><?=h($_SESSION['username'])?></a>
        <?php endif; ?>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="/management.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> マイページ</a></li>
          <li><a href="/settings/nickname.php"><span class="glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span> 設定</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="/logout.php?token=<?=h(generate_token())?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト</a></li>
        </ul>
      </div>
      <a href="/make.php"><button id="make-questionnaire" class="btn btn-primary pull-right" <?=$_SESSION['username']=='guest'?'disabled':''?>><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> アンケート作成</button></a>
    </div>
  </div>
</header>
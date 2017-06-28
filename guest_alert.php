<?php if ($_SESSION['username'] == 'guest'): ?>
<div class="container">
  <div class="alert alert-warning">
    ゲストユーザでログインしています.<br>
    アンケート内容の確認はできますが，アンケートの作成および回答はできません.
  </div>
</div>
<?php endif; ?>

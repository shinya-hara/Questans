<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created,updated,owner,user_name from questionnaires,users where q_id = ? && owner = user_id");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetch();
    // 質問を取得
    $stmt = $dbh->prepare("select * from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, $_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    $qCount = count($questions);  // 質問数
    // 選択肢を取得
    $stmt = $dbh->prepare("select * from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, $_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
    $cCount = count($choices);  // 選択肢数
    // アンケートの回答数をカウント
    $stmt = $dbh->prepare("select count(*) from answers where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answeredCount = $stmt->fetchColumn();
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
<?php include __DIR__.'/contentHeader.php'; ?>
<div class="container">
  <p>以下の内容で回答結果を送信します．よろしいですか？</p>
  <form method="post" action="ans_send.php" role="form" data-toggle="validator">
    <button type="button" class="btn btn-default" id="edit">修正</button>
    <input type="submit" class="btn btn-primary" id="submit" value="OK">
    <table class="table text-center">
      <thead>
        <tr>
          <th class="text-center td-num none">番号</th>
          <th>質問</th>
          <?php for ($i = 0; $i < $cCount; $i++): ?>
          <th class="text-center"><?=h($choices[$i]['choice'])?></th>
          <?php endfor; ?>
        </tr>
      </thead>
      <tbody>
        <?php for ($i = 0; $i < $qCount; $i++): ?>
        <tr>
          <td class="td-num none"><?=$i+1?></td>
          <td class="text-left"><?=h($questions[$i]['question'])?></td>
          <?php for ($j = 0; $j < $cCount; $j++): ?>
            <?php if ($_POST['a'.($i+1)] == ($j+1)): ?>
            <td><input type="radio" name="a<?=$i+1?>" value="<?=$choices[$j]['c_num']?>" checked></td>
            <?php else: ?>
            <td><input type="radio" name="a<?=$i+1?>" value="<?=$choices[$j]['c_num']?>" disabled></td>
            <?php endif; ?>
          <?php endfor; ?>
        </tr>
        <?php endfor; ?>
      </tbody>
    </table>
    <input type="hidden" name="q_id" value="<?=$_POST['q_id']?>">
    <input type="hidden" name="q_cnt" value="<?=$qCount?>">
  </form>
</div>

<script>
$(function() {
  $('[data-toggle="tooltip"]').tooltip();
  
  // 修正する（回答画面に戻る）
  $('#edit').on('click', function() {
    $.post('ans.php',
    {
      'q_id': <?=$_POST['q_id']?>,
      <?php for ($i=1;$i<=(int)$_POST['q_cnt'];$i++) {
        echo "'a".$i."':".$_POST['a'.$i].",";
      } ?>
    },
    function(data) {
      $('main').html(data);
    });
  });
  
  // 回答送信
  $('form').submit(function(event){
    event.preventDefault();
    var f = $(this);
    $.ajax({
      url: f.prop('action'),
      type: f.prop('method'),
      data: f.serialize(),
      timeout: 10000,
      dataType: 'text'
    })
    .done(function(data) {
      // 通信が成功したときの処理
      $('main').html(data);
    })
    .fail(function(data) {
      // 通信が失敗したときの処理
      alert('通信に失敗しました．');
    })
  });
  
  // ページから離れる際に確認
  var isChanged = true;  // フォームの状態を表すフラグ
  $(window).on('beforeunload', function() {
    if (isChanged) {
      return "このページを離れようとしています．";
    }
  });
  $('#submit, button#edit').on('click', function() {
    // ボタンを押した際にフラグを落とす
    isChanged = false;
  });
});
</script>
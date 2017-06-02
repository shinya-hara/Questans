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
    // 回答結果を取得
    $stmt = $dbh->prepare("SELECT count(answer) FROM answers AS a, ans_detail AS d WHERE a.ans_id = d.ans_id AND q_id = ? AND q_num = ? AND answer = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT); // アンケートIDをbind
    for ($i = 1; $i <= $qCount; $i++) {
      $stmt->bindValue(2, (int)$i, PDO::PARAM_INT);   // 質問番号をbind
      for ($j = 1; $j <= $cCount; $j++) {
        $stmt->bindValue(3, (int)$j, PDO::PARAM_INT); // 選択肢をbind
        $stmt->execute();
        $result = $stmt->fetch();
        $results_array[$i][$j] = $result['count(answer)'];  // 二次元配列に結果を格納
        // echo $result['count(answer)'].' ';
      }
      // echo '<br>';
    }
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
<?php include __DIR__.'/flash.php'; ?>
<?php include __DIR__.'/contentHeader.php'; ?>
<div class="container">
  <button type="button" class="btn btn-default" id="back" data-id="<?=(int)$_POST['q_id']?>">Back</button>
  
  <h3>結果</h3>
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
        <td><?=$results_array[$i+1][$j+1]?></td>
        <?php endfor; ?>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</div>
<script>
  $(function() {
    // 詳細に戻る
    $('#back').on('click', function() {
      $.post('detail.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
     // ユーザページ
    $('span.owner').on('click', function() {
      $.post('user.php',
      {
        'req_user_id': <?=$questionnaires['owner']?>
      },
      function(data) {
        $('main').html(data);
      });
    });
  });
</script>

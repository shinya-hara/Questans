<?php
require_once __DIR__.'/db_info.php';
require_once __DIR__.'/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created,updated,owner,user_name from questionnaires,users where q_id = ? && owner = user_id");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetch();
    // 質問の取得
    $stmt = $dbh->prepare("select q_num,question from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    // 選択肢の取得
    $stmt = $dbh->prepare("select c_num,choice from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "詳細の取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<?php include __DIR__.'/flash.php'; ?>
<button type="button" class="btn btn-default" id="back">Back</button>
<h3>
  <?=$questionnaires['title']?><br>
  <small>
    Owner <span class="owner"><?=$questionnaires['user_name']?></span><br>
    Created at <?=$questionnaires['created']?><br>
    Updated at <?=is_null($questionnaires['updated'])?"---":$questionnaires['updated']?>
  </small>
</h3>
<!-- 質問 -->
<table class="table">
  <thead>
    <th>番号</th><th>質問</th>
  </thead>
  <tbody>
    <?php foreach($questions as $row): ?>
    <tr><td><?=$row['q_num']?></td><td><?=$row['question']?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>
<!-- 選択肢 -->
<table class="table">
  <thead>
    <th>番号</th><th>選択肢</th>
  </thead>
  <tbody>
    <?php foreach($choices as $row): ?>
    <tr><td><?=$row['c_num']?></td><td><?=$row['choice']?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php if ($questionnaires['owner'] == $_SESSION['user_id']): ?>
<button type="button" class="btn btn-primary" id="edit" data-id="<?=$_POST['id']?>">Edit</button>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal">Delete</button>
<?php else: ?>
<form method="post" action="answer.php">
  <input type="hidden" name="q_id" value="<?=$_POST['id']?>">
  <input type="submit" class="btn btn-primary" id="answer" value="Answer this Questionnaire">
</form>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="delModalLabel">アンケートの削除</h4>
      </div>
      <div class="modal-body" id="modal-msg">
        このアンケートを削除しますか？<br>
        この操作は取り消せません．
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="delete" data-id="<?=$_POST['id']?>">Delete</button>
        <button type="button" class="btn btn-primary" id="close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
  $(function() {
    // 一覧に戻る
    $('#back').on('click', function() {
      if ("<?=$_SESSION['from']?>" === "userpage") {
        $.post('user.php',
        {
          'req_user_id': <?=$_SESSION['prev_req_user_id']?>
        },
        function(data) {
          $('main').html(data);
        });
      } else if ("<?=$_SESSION['from']?>" === "list") {
        $.get('list.php', function(data) {
          $('main').html(data);
        });
      }
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
    
    // 編集
    $('#edit').on('click', function() {
      $.post('edit.php',
      {
        'id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    
    // 削除
    $('#close').hide();
    $('#delete').on('click', function() {
      $('#delete').prop('disabled', true);
      $('#modal-msg').html("削除中...");
      $.post('delete.php',
      {
        'id': $(this).attr('data-id')
      },
      function(data) {
        // ダイアログメッセージの変更
        $('#modal-msg').html(data);
        $('#delete, #cancel').hide();
        $('#close').show();
        // ダイアログを閉じるとアンケート一覧に戻る
        $('#delModal').on('hidden.bs.modal', function() {
          $.post('user.php',
          {
            'req_user_id': <?=$_SESSION['user_id']?>
          },
          function(data) {
            $('.modal-backdrop').remove();
            $('main').html(data);
          });
        });
      });
    });
  });
</script>
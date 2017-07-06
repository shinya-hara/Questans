<?php
require_once __DIR__.'/../db_info.php';
require_once __DIR__.'/../functions.php';
require_admin_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created,updated,owner,isPrivate,user_name from questionnaires,users where q_id = ? && owner = user_id");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetch();
    // 質問の取得
    $stmt = $dbh->prepare("select q_num,question from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    // 選択肢の取得
    $stmt = $dbh->prepare("select c_num,choice from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
    // アンケートの回答数をカウント
    $stmt = $dbh->prepare("select count(*) from answers where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answeredCount = $stmt->fetchColumn();
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
<?php include __DIR__.'/../flash.php'; ?>
<?php include __DIR__.'/../contentHeader.php'; ?>
<div class="container">
  <a href="/admin/questionnaires_management.php" class="btn btn-default">戻る</a>
  <?php if ($questionnaires['isPrivate']): ?>
  <div class="alert alert-warning">
    このアンケートは非公開に設定されています．
  </div>
  <?php endif; ?>
  <!-- 質問 -->
  <table class="table">
    <thead>
      <tr><th class="text-center td-num">番号</th><th>質問</th></tr>
    </thead>
    <tbody>
      <?php foreach($questions as $row): ?>
      <tr><td class="text-center td-num"><?=$row['q_num']?></td><td><?=h($row['question'])?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <!-- 選択肢 -->
  <table class="table">
    <thead>
      <tr><th class="text-center td-num">番号</th><th>選択肢</th></tr>
    </thead>
    <tbody>
      <?php foreach($choices as $row): ?>
      <tr><td class="text-center td-num"><?=$row['c_num']?></td><td><?=h($row['choice'])?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <button type="button" class="btn btn-success" id="result" data-id="<?=$_POST['q_id']?>">結果を見る</button>
  <button type="button" class="btn btn-primary" id="edit" data-id="<?=$_POST['q_id']?>">編集</button>
  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal">削除</button>
  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#compDelModal">完全削除</button>
  <input type="hidden" name="q_id" value="<?=$_POST['q_id']?>">
  
  <!-- 削除Modal -->
  <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="delModalLabel">アンケートの削除</h4>
        </div>
        <div class="modal-body" id="del-modal-msg">
          アンケートを削除しますか？<br>
          この操作を行うと，一般ユーザからは見えなくなりますが，<strong>データベースからは削除されません</strong>．<br>
          この操作は取り消せません．
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">キャンセル</button>
          <button type="button" class="btn btn-danger" id="delete" data-id="<?=$_POST['q_id']?>" data-loading-text="削除中...">削除</button>
          <button type="button" class="btn btn-primary" id="close" data-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>
  <!-- 完全削除Modal -->
  <div class="modal fade" id="compDelModal" tabindex="-1" role="dialog" aria-labelledby="compDelModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="compDelModalLabel">アンケートの完全削除</h4>
        </div>
        <div class="modal-body" id="comp-del-modal-msg">
          アンケートを削除しますか？<br>
          この操作を行うと，<strong>データベースから完全に削除され，回答結果も完全に削除されます</strong>．<br>
          この操作は取り消せません．
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="comp-cancel" data-dismiss="modal">キャンセル</button>
          <button type="button" class="btn btn-danger" id="comp-delete" data-id="<?=$_POST['q_id']?>" data-loading-text="削除中...">削除</button>
          <button type="button" class="btn btn-primary" id="comp-close" data-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // 結果を見る
    $('#result').on('click', function() {
      $.post('result.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    // 編集
    $('#edit').on('click', function() {
      $.post('edit.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    // 削除
    $('#close').hide();
    $('#delete').on('click', function() {
      $(this).button('loading');
      $.post('delete.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        // ダイアログメッセージの変更
        $('#del-modal-msg').html(data);
        $('#delete, #cancel').hide();
        $('#close').show();
        // ダイアログを閉じるとアンケート一覧に戻る
        $('#delModal').on('hidden.bs.modal', function() {
          $('.modal-backdrop').remove();
          location.href = "questionnaires_management.php";
        });
      });
    });
    // 完全削除
    $('#comp-close').hide();
    $('#comp-delete').on('click', function() {
      $(this).button('loading');
      $.post('comp_delete.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        // ダイアログメッセージの変更
        $('#comp-del-modal-msg').html(data);
        $('#comp-delete, #comp-cancel').hide();
        $('#comp-close').show();
        // ダイアログを閉じるとアンケート一覧に戻る
        $('#compDelModal').on('hidden.bs.modal', function() {
          $('.modal-backdrop').remove();
          location.href = "questionnaires_management.php";
        });
      });
    });
  });
</script>
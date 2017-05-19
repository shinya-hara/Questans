<?php
session_start();
$_SESSION['update'] = 1;
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    $id = (int)$_POST['id'];
    $_SESSION['update_id'] = $id;
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title from questionnaries where q_id = ?");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $questionnaries = $stmt->fetch(PDO::FETCH_ASSOC);
    // 質問の取得
    $stmt = $dbh->prepare("select question from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_NUM);
    // json形式に変換
    $jsonQs = json_encode($questions);
    // 選択肢の取得
    // $stmt = $dbh->prepare("select c_num,choice from choices where q_id = ? order by c_num");
    // $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    // $stmt->execute();
    // $choices = $stmt->fetchAll();
  } catch (PDOException $e) {
    // $_SESSION['status'] = "danger";
    // $_SESSION['flash_msg'] = "質問の取得に失敗しました．";
    // $_SESSION['flash_flag'] = true;
    echo '質問の取得に失敗しました．';
  }
} catch (PDOException $e) {
  // $_SESSION['status'] = "danger";
  // $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  // $_SESSION['flash_flag'] = true;
  echo 'データベースの接続に失敗しました．';
}
?>
<button type="button" class="btn btn-primary" id="back" data-id="<?=(int)$_POST['id']?>">BACK</button>
<h3>編集</h3>
<form method="post" action="ajax.php" data-toggle="validator" role="form">
  <div class="form-group has-feedback">
    <label for="title" class="control-label">タイトル</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="アンケートのタイトル" data-error="タイトルを入力してください．" required>
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <div class="help-block with-errors"></div>
  </div>
  <input type="button" name="add" value="Add new question" class="btn btn-info center-block" id="addBtn">
  <div class="form-group">
    <input class="btn btn-primary btn-block" type="submit" value="更新">
  </div>
  <input type="hidden" name="num" value='0'><!-- 質問数 -->
</form>
<script>
  $(function() {
    // 詳細に戻る
    $('#back').on('click', function() {
      $.post('detail.php',
      {
        'id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    // 削除ボタンの属性を変更する
    // num: 押された削除ボタンの質問番号
    function update(num) {
      if (num >= cnt) {
        cnt--;
        $('input[name="num"]').attr('value', cnt);
        return;
      }
      $('#q'+(num+1)+'-group').attr('id', 'q'+num+'-group');
      $('#q'+num+'-group>label').attr('for', 'q'+num).text('質問'+num);
      
      $('#delBtn'+(num+1)).attr({
        'id': 'delBtn'+num,
        'value': 'Delete Q'+num
      });
      $('#q'+(num+1)).attr({
        'id': 'q'+num,
        'name': 'q'+num,
        'placeholder': '質問'+num+'の内容'
      });
      $('#delBtn'+num).off();
      $('#delBtn'+num).on('click', { num: num }, delTextarea);
      update(num+1);
    }
    // テキストエリアを削除する
    var delTextarea = function(e) {
      $('#q'+e.data.num+'-group').remove();
      update(e.data.num);
    }
    
    // テキストエリアを増減させるボタンを設置
    // jQueryにcloneという便利そうなメソッドを発見(2017/5/16)
    var cnt = 0;  // 質問数
    $('#addBtn').on('click', function () {
      cnt++;
      var html = '<div class="form-group has-feedback" id="q'+cnt+'-group">\
                  <div class="form-group"><input type="button" name="del" value="Delete Q'+cnt+'" class="btn btn-danger btn-xs pull-right" id="delBtn'+cnt+'" tabindex="-1"></div>\
                  <label for="q'+cnt+'" class="control-label">質問'+cnt+'</label>\
                  <textarea class="form-control" id="q'+cnt+'" name="q'+cnt+'" rows="3" placeholder="質問'+cnt+'の内容" data-error="質問を入力してください．不要な場合は削除してください．" required></textarea>\
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                  <div class="help-block with-errors"></div>\
                  </div>';
      $('#addBtn').before(html);
      $('input[name="num"]').attr('value', cnt);
      $('#delBtn'+cnt).on('click', { num: cnt }, delTextarea);
      $('#q'+cnt).focus();
      $('form').validator('update');
    });
    
    // json形式の質問をパースし格納
    var question = JSON.parse('<?=$jsonQs?>');
    // 前回のフォームの内容を再現
    $('#title').val("<?=$questionnaries['title']?>");
    for (var i = 0, len = question.length; i < len; i++) {
      $('#addBtn').trigger('click');
      $('#q'+cnt).val(question[cnt-1]);
    }
    $('form').validator('validate');
  });
</script>
<?php
session_start();
$_SESSION['update'] = 1;
require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
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
    $stmt = $dbh->prepare("select choice from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, (int)$_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll(PDO::FETCH_NUM);
    // json形式に変換
    $jsonCs = json_encode($choices);
  } catch (PDOException $e) {
    // $_SESSION['status'] = "danger";
    // $_SESSION['flash_msg'] = "質問の取得に失敗しました．";
    // $_SESSION['flash_flag'] = true;
    echo '詳細の取得に失敗しました．';
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
    <!--<div class="help-block with-errors"></div>-->
  </div>
  <div class="row">
    <div class="col-sm-6">
      <input type="button" value="Add new question" class="btn btn-info center-block" id="addQBtn">
    </div>
    <div class="col-sm-6">
      <input type="button" value="Add new choice" class="btn btn-info center-block" id="addCBtn">
    </div>
  </div>
  <div class="form-group">
    <input class="btn btn-primary btn-block" type="submit" value="更新">
  </div>
  <input type="hidden" name="q_num" value='0'><!-- 質問数 -->
  <input type="hidden" name="c_num" value='0'><!-- 選択肢数 -->
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
      function q_update(num) {
        if (num >= q_cnt) {
          q_cnt--;
          $('input[name="q_num"]').attr('value', q_cnt);
          return;
        }
        $('#q'+(num+1)+'-group').attr('id', 'q'+num+'-group');
        $('#q'+num+'-group>label').attr('for', 'q'+num).text('質問'+num);
        
        $('#delQBtn'+(num+1)).attr({
          'id': 'delQBtn'+num,
          'value': 'Delete Q'+num
        });
        $('#q'+(num+1)).attr({
          'id': 'q'+num,
          'name': 'q'+num,
          'placeholder': '質問'+num+'の内容'
        });
        $('#delQBtn'+num).off();
        $('#delQBtn'+num).on('click', { num: num }, delQuestion);
        q_update(num+1);
      }
      // テキストエリアを削除する
      var delQuestion = function(e) {
        $('#q'+e.data.num+'-group').remove();
        q_update(e.data.num);
        $('form').validator('update');
      }
      
      // テキストエリアを増減させるボタンを設置
      // jQueryにcloneという便利そうなメソッドを発見(2017/5/16)
      var q_cnt = 0;  // 質問数
      $('#addQBtn').on('click', function () {
        q_cnt++;
        var html = '<div class="form-group has-feedback" id="q'+q_cnt+'-group">\
                    <div class="form-group"><input type="button" name="del" value="Delete Q'+q_cnt+'" class="btn btn-danger btn-xs pull-right" id="delQBtn'+q_cnt+'" tabindex="-1"></div>\
                    <label for="q'+q_cnt+'" class="control-label">質問'+q_cnt+'</label>\
                    <textarea class="form-control" id="q'+q_cnt+'" name="q'+q_cnt+'" rows="3" placeholder="質問'+q_cnt+'の内容" data-error="質問を入力してください．不要な場合は削除してください．" required></textarea>\
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                    <!--<div class="help-block with-errors"></div>-->\
                    </div>';
        $('#addQBtn').before(html);
        $('input[name="q_num"]').attr('value', q_cnt);
        $('#delQBtn'+q_cnt).on('click', { num: q_cnt }, delQuestion);
        $('#q'+q_cnt).focus();
        $('form').validator('update');
      });
      
      // 削除ボタンの属性を変更する
      // num: 押された削除ボタンの選択肢番号
      function c_update(num) {
        if (num >= c_cnt) {
          c_cnt--;
          $('input[name="c_num"]').attr('value', c_cnt);
          return;
        }
        $('#c'+(num+1)+'-group').attr('id', 'c'+num+'-group');
        $('#c'+num+'-group>label').attr('for', 'c'+num).text('選択肢'+num);
        
        $('#delCBtn'+(num+1)).attr({
          'id': 'delCBtn'+num,
          'value': 'Delete C'+num
        });
        $('#c'+(num+1)).attr({
          'id': 'c'+num,
          'name': 'c'+num,
          'placeholder': '質問'+num+'の内容'
        });
        $('#delCBtn'+num).off();
        $('#delCBtn'+num).on('click', { num: num }, delChoice);
        c_update(num+1);
      }
      // テキストエリアを削除する
      var delChoice = function(e) {
        $('#c'+e.data.num+'-group').remove();
        c_update(e.data.num);
        $('form').validator('update');
      }
      // 選択肢
      var c_cnt = 0;  // 選択肢数
      $('#addCBtn').on('click', function () {
        c_cnt++;
        var html = '<div class="form-group has-feedback" id="c'+c_cnt+'-group">\
                    <div class="form-group"><input type="button" name="del" value="Delete C'+c_cnt+'" class="btn btn-danger btn-xs pull-right" id="delCBtn'+c_cnt+'" tabindex="-1"></div>\
                    <label for="c'+c_cnt+'" class="control-label">選択肢'+c_cnt+'</label>\
                    <textarea class="form-control" id="c'+c_cnt+'" name="c'+c_cnt+'" rows="3" placeholder="選択肢'+c_cnt+'の内容" data-error="選択肢を入力してください．不要な場合は削除してください．" required></textarea>\
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                    <!--<div class="help-block with-errors"></div>-->\
                    </div>';
        $('#addCBtn').before(html);
        $('input[name="c_num"]').attr('value', c_cnt);
        $('#delCBtn'+c_cnt).on('click', { num: c_cnt }, delChoice);
        $('#c'+c_cnt).focus();
        $('form').validator('update');
      });
    
    // json形式の質問をパースし格納
    var question = JSON.parse('<?=$jsonQs?>');
    var choice = JSON.parse('<?=$jsonCs?>');
    // 前回のフォームの内容を再現
    $('#title').val("<?=$questionnaries['title']?>");
    for (var i = 0, len = question.length; i < len; i++) {
      $('#addQBtn').trigger('click');
      $('#q'+q_cnt).val(question[q_cnt-1]);
    }
    for (var i = 0, len = choice.length; i < len; i++) {
      $('#addCBtn').trigger('click');
      $('#c'+c_cnt).val(choice[c_cnt-1]);
    }
    $('form').validator('validate');
  });
</script>
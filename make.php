<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>作成 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>作成</h2>
      <form method="post" action="confirm.php">
        <div id="questions">
          <input type="button" name="add" value="Add new question" class="btn btn-info center-block" id="addBtn">
        </div>
        <input class="btn btn-primary btn-block" type="submit" value="作成">
      </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
    // 削除ボタンの属性を変更する
    // num: 押された削除ボタンの質問番号
    function update(num) {
      if (num >= cnt) {
        cnt--;
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
      $('#delBtn'+num).on('click', { num: num }, delTA);
      update(num+1);
    }
    // テキストエリアを削除する
    var delTA = function(e) {
      $('#q'+e.data.num+'-group').remove();
      update(e.data.num);
    }
    
    // テキストエリアを増減させるボタンを設置
    var cnt = 0;  // 質問数
    $('#addBtn').on('click', function () {
      cnt++;
      var html = '<div class="form-group" id="q'+cnt+'-group">\
                  <label for="q'+cnt+'">質問'+cnt+'</label>\
                  <input type="button" name="del" value="Delete Q'+cnt+'" class="btn btn-danger btn-xs pull-right" id="delBtn'+cnt+'">\
                  <textarea class="form-control" id="q'+cnt+'" name="q'+cnt+'" rows="3" placeholder="質問'+cnt+'の内容"></textarea>\
                  </div>';
      $('#addBtn').before(html);
      $('#delBtn'+cnt).on('click', { num: cnt }, delTA);
    });
    $('#addBtn').trigger('click');
    </script>
  </body>
</html>
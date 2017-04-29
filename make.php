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
      <form method="post" action="index.php">
        <h2>作成</h2>
        <div class="form-group">
          <label for="q1">設問1</label>
          <textarea class="form-control" id="q1" name="q1" rows="3" placeholder="設問1の内容"></textarea>
        </div>
        <div class="form-group">
          <label for="q2">設問2</label>
          <textarea class="form-control" id="q2" name="q2" rows="3" placeholder="設問2の内容"></textarea>
        </div>
        <div class="form-group">
          <label for="q3">設問3</label>
          <textarea class="form-control" id="q3" name="q3" rows="3" placeholder="設問3の内容"></textarea>
        </div>
        <div class="form-group">
          <label for="q4">設問4</label>
          <textarea class="form-control" id="q4" name="q4" rows="3" placeholder="設問4の内容"></textarea>
        </div>
        <div class="form-group">
          <label for="q5">設問5</label>
          <textarea class="form-control" id="q5" name="q5" rows="3" placeholder="設問5の内容"></textarea>
        </div>
        <input type="submit" value="作成" class="btn btn-primary btn-block">
      </form>
    </div>
  </body>
</html>
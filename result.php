<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
?>
<?php include __DIR__.'/flash.php'; ?>
<button type="button" class="btn btn-default" id="back" data-id="<?=(int)$_POST['id']?>">Back</button>
まだ結果を表示できませーん
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
  });
</script>

<div class="questionnaireMainHeader">
  <div class="container">
    <h2><?=$questionnaires['title']?></h2>
  </div>
</div>
<div class="questionnaireAsideHeader">
  <div class="container clearfix">
    <div class="pull-left username"><?=h($questionnaires['user_name'])?></div>
    <div class="pull-left" <?=is_null($questionnaires['updated'])?'':'data-toggle="tooltip" data-placement="bottom" title="'.h($questionnaires['created']).' に作成"'?>>
      <?=is_null($questionnaires['updated'])?h($questionnaires['created'])." に作成":h($questionnaires['updated'])." に更新"?>
    </div>
  </div>
</div>
<div class="questionnaireMainHeader">
  <div class="container">
    <div class="row flex-center">
      <div class="col-sm-10">
        <h2 class="questoinnaireMainHeaderTitle"><?=$questionnaires['title']?></h2>
      </div>
      <div class="col-sm-2">
        <div class="text-center showHeaderAnswered">
          <!--<span class="showHeaderAnsweredText">回答数</span>-->
          <!--<span class="showHeaderAnsweredCount">12</span>-->
          <div class="text-center showHeaderAnsweredCount"><?=$answeredCount?></div>
          <div class="text-center showHeaderAnsweredText">回答数</div>
        </div>
        <?php if ($questionnaires['owner'] != $_SESSION['user_id']): ?>
        <!--<button type="button" class="btn btn-default btn-block" id="headerAnswerBtn">回答する</button>-->
        <?php endif; ?>
      </div>
    </div>
    
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
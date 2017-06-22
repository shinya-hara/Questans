<table class="table table-hover list-table">
  <thead>
    <th class="none text-center td-num">番号</th>
    <th>タイトル</th>
    <th>作成者</th>
    <th class="none">作成日時</th>
    <th class="none">更新日時</th>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($questionnaires as $row): ?>
    <tr data-id="<?=h($row['q_id'])?>">
      <td class="none text-center td-num"><?=$i?></td>
      <td><?php if ($answered_flg[$row['q_id']]): ?><span class="label label-info">回答済み</span> <?php endif; ?><?=h($row['title'])?></td>
      <td class="owner" data-userid="<?=$row['owner']?>"><?=h($users[$row['owner']])?></td>
      <td class="none"><?=substr(h($row['created']),0,16)?></td>
      <td class="none"><?=is_null($row['updated'])?"---":substr(h($row['updated']),0,16)?></td>
    </tr>
    <?php $i++; endforeach; ?>
  </tbody>
</table>
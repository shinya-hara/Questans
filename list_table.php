<?php ini_set( 'display_errors', 1 ); ?>
<table class="table table-hover" id="list">
  <thead>
    <th>番号</th>
    <th>タイトル</th>
    <th>作成者</th>
    <th>作成日時</th>
    <th>更新日時</th>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($questionnaries as $row): ?>
    <tr data-id="<?=h($row['q_id'])?>">
      <td><?=$i?></td>
      <td><?=h($row['title'])?></td>
      <td class="owner" data-userid="<?=$row['owner']?>"><?=h($users[$row['owner']])?></td>
      <td><?=h($row['created'])?></td>
      <td><?=is_null($row['updated'])?"---":h($row['updated'])?></td>
    </tr>
    <?php $i++; endforeach; ?>
  </tbody>
</table>
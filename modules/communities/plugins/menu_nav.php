<?php  
$comm = db::get_string("SELECT `ID`,`PRIVATE` FROM `COMMUNITIES` WHERE `URL` = ? LIMIT 1", [esc(get('id'))]);

?>
<a href="/m/communities/info/?id=<?=$comm['ID']?>">
<?=icons('info', 18, 'fa-fw')?> <?=lg('О сообществе')?>
</a>  
<a href="/m/block/comm_list/?id=<?=$comm['ID']?>">
<?=icons('ban', 18, 'fa-fw')?> <?=lg('История блокировок')?>
</a>
<a href="/m/communities/journal/?id=<?=$comm['ID']?>">
<?=icons('newspaper-o', 18, 'fa-fw')?> <?=lg('Журнал')?>
</a>
<?
  
if ($comm['PRIVATE'] == 2 && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ADMINISTRATION` > ?", [$comm['ID'], user('ID'), 0]) > 0) {
  
  ?>
  <a href="/m/communities/applications/?id=<?=$comm['ID']?>">
  <?=icons('plus', 18, 'fa-fw')?> <?=lg('Заявки на вступление')?>
  </a>
  <?
  
}

if (access('communities', null, 1) == true) {
  
  ?>
  <a href="/m/block/comm/?id=<?=$comm['ID']?>&<?=TOKEN_URL?>">
  <?=icons('ban', 18, 'fa-fw')?> <?=lg('Заблокировать')?>
  </a>
  <?
  
}
?>
  
<span onclick="open_or_close('panel-top-modal2')">
<?=icons('times', 18, 'fa-fw')?> <?=lg('Закрыть')?>
</span>
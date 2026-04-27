<?php
$info = db::get_string("SELECT * FROM `INFO` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(stripslashes(lg($info['NAME'])));

if (!isset($info['ID'])) {
  
  error('Неверная директива');
  redirect('/m/info/');

}

if (access('info', null) == true){
  
  require_once (ROOT.'/modules/info/plugins/delete.php');
  
  ?>
  <div class='list'>
  <a href='/m/info/edit/?id=<?=$info['ID']?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a href='/m/info/show/?id=<?=$info['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  </div>
  <?
  
}

?>
<div class='list'>
<b><?=lg(tabs($info['NAME']))?></b><br />
<span class='time'><?=lg('Последние изменения')?>: <?=ftime($info['TIME'])?></span>
<br /><br />
<?=lg(text($info['MESSAGE']))?>
</div>
<?

back('/m/info/');
acms_footer();
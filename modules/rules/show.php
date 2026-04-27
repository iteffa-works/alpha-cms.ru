<?php
$rules = db::get_string("SELECT * FROM `RULES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(stripslashes(lg($rules['NAME'])));

if (!isset($rules['ID'])) {
  
  error('Неверная директива');
  redirect('/m/rules/');

}

if (access('rules', null) == true){
  
  require_once (ROOT.'/modules/rules/plugins/delete.php');
  
  ?>
  <div class='list'>
  <a href='/m/rules/edit/?id=<?=$rules['ID']?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a href='/m/rules/show/?id=<?=$rules['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  </div>
  <?
  
}

?>
<div class='list'>
<b><?=lg(tabs($rules['NAME']))?></b><br />
<span class='time'><?=lg('Последние изменения')?>: <?=ftime($rules['TIME'])?></span>
<br /><br />
<?=lg(text($rules['MESSAGE']))?>
</div>
<?

back('/m/rules/');
acms_footer();
<div class='list-body'>
<div class='list-menu list-menu-title'><?=icons('map-marker', 20, 'fa-fw')?> <?=lg('Место проживания')?></div>
  
<div class='profile_info'>
  
<?php
$c_f = db::get_string("SELECT * FROM `COUNTRY` WHERE `NAME` = ? LIMIT 1", [esc($settings['COUNTRY'])]);
?>

<div class='profile_info_list'>
<?=icons('map-marker', 20, 'fa-fw')?> <span><?=lg('Страна')?>: <?=($settings['COUNTRY'] != null ? tabs($settings['COUNTRY']) : lg('не указана'))?> <?=($c_f['ID'] != null ? '<img src="/style/country/'.$c_f['ID'].'.png">' : null)?>
</div>
  
<div class='profile_info_list'>
<?=icons('map-marker', 20, 'fa-fw')?> <span><?=lg('Регион')?>: <?=($settings['REGION'] != null ? tabs($settings['REGION']) : lg('не указан'))?>
</div>
  
<div class='profile_info_list'>
<?=icons('map-marker', 20, 'fa-fw')?> <span><?=lg('Город')?>: <?=($settings['CITY'] != null ? tabs($settings['CITY']) : lg('не указан'))?>
</div>
  
</div>  
  
<?php

if (user('ID') > 0 && $account['ID'] == user('ID')){
  
  ?>
  <div class='list-menu'>
  <a class='btn-o' href='/account/form/?id=<?=$account['ID']?>&get=geo&<?=TOKEN_URL?>'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  </div>
  <?
  
}

?>

</div>
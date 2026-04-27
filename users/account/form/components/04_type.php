<div class='list-body'>
<div class='list-menu list-menu-title'><?=icons('user', 20, 'fa-fw')?> <?=lg('Типаж')?></div>
  
<div class='profile_info'>

<div class='profile_info_list'>
<?=icons('arrows-v', 20, 'fa-fw')?> <span><b><?=lg('Рост')?>:</b> <?=($settings['HEIGHT'] != null ? tabs($settings['HEIGHT']) : lg('не указан'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('arrows-h', 20, 'fa-fw')?> <span><b><?=lg('Вес')?>:</b> <?=($settings['WIDTH'] != null ? tabs($settings['WIDTH']) : lg('не указан'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('eye', 20, 'fa-fw')?> <span><b><?=lg('Цвет глаз')?>:</b> <?=($settings['COLOR_EYE'] != null ? tabs($settings['COLOR_EYE']) : lg('не указан'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('user', 20, 'fa-fw')?> <span><b><?=lg('Цвет волос')?>:</b> <?=($settings['COLOR_HAIR'] != null ? tabs($settings['COLOR_HAIR']) : lg('не указан'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('user', 20, 'fa-fw')?> <span><b><?=lg('Характер')?>:</b> <?=($settings['MY_NATURE'] != null ? tabs($settings['MY_NATURE']) : lg('не указан'))?></span>
</div>
  
<?php
  
if ($settings['MY_BODY'] == 1){
  
  $body = lg('Не важно');

}elseif ($settings['MY_BODY'] == 2){
  
  $body = lg('Обычное');

}elseif ($settings['MY_BODY'] == 3){
  
  $body = lg('Худощавое');

}elseif ($settings['MY_BODY'] == 4){
  
  $body = lg('Спортивное');

}elseif ($settings['MY_BODY'] == 5){
  
  $body = lg('Мускулистое');

}elseif ($settings['MY_BODY'] == 6){
  
  $body = lg('Плотное');

}elseif ($settings['MY_BODY'] == 7){
  
  $body = lg('Полное');

}else{
  
  $body = lg('не указано');

}
  
?>
  
<div class='profile_info_list'>
<?=icons('user', 20, 'fa-fw')?> <span><b><?=lg('Телосложение')?>:</b> <?=$body?></span>
</div>  
  
</div>  
  
<?php

if (user('ID') > 0 && $account['ID'] == user('ID')){
  
  ?>
  <div class='list-menu'>
  <a class='btn-o' href='/account/form/?id=<?=$account['ID']?>&get=type&<?=TOKEN_URL?>'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  </div>
  <?
  
}

?>

</div>
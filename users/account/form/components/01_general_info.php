<div class='list-body'>
<div class='list-menu list-menu-title'><?=icons('info-circle', 20, 'fa-fw')?> <?=lg('Общая информация')?></div>
  
<div class='profile_info'>
  
<?php
$access = db::get_string("SELECT `NAME` FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [$account['ACCESS']]);
?>

<div class='profile_info_list'>
<?=icons('lock', 20, 'fa-fw')?> <span><?=lg('Права')?>: <small class='count'><?=lg(tabs($access['NAME']))?></small></span>
</div>
  
<div class='profile_info_list'>
<?=icons('bar-chart', 20, 'fa-fw')?> <span><?=lg('Рейтинг')?>: <small class='count'><?=tabs($account['RATING'])?></small></span>
</div>  

<?php
if ($settings['G_R'] == 0 || $settings['M_R'] == 0 || $settings['D_R'] == 0){
  
  $date = lg('не указана');
  $age = lg('не указан');
  
}else{
  
  $date = (str($settings['D_R']) > 1 ? $settings['D_R'] : '0'.$settings['D_R']).".".(str($settings['M_R']) > 1 ? $settings['M_R'] : '0'.$settings['M_R']).".".$settings['G_R'];
  $age = _age($account['ID'], age($account['ID'], $settings['G_R'], $settings['M_R'], $settings['D_R']), array(lg('год'), lg('года'), lg('лет')));
  
}
?>

<div class='profile_info_list'>
<?=icons('address-book-o', 20, 'fa-fw')?> <span><?=lg('Имя')?>: <?=($settings['NAME'] != null ? tabs($settings['NAME']) : lg('не указано'))?></span>
</div>

<div class='profile_info_list'>
<?=icons('address-book-o', 20, 'fa-fw')?> <span><?=lg('Фамилия')?> <?=($settings['SURNAME'] != null ? tabs($settings['SURNAME']) : lg('не указана'))?></span>
</div>

<div class='profile_info_list'>
<?=icons('address-book-o', 20, 'fa-fw')?> <span><?=lg('Возраст')?>: <?=($age != null ? tabs($age) : lg('не указан'))?></span>
</div>

<div class='profile_info_list'>
<?=icons('gift', 20, 'fa-fw')?> <span><?=lg('Дата рождения')?>: <?=$date?></span>
</div>

<div class='profile_info_list'>
<?=icons('user-plus', 20, 'fa-fw')?> <span><?=lg('Дата регистрации')?>: <?=ftime($account['DATE_CREATE'])?></span>
</div>

<div class='profile_info_list'>
<?=icons('clock-o', 20, 'fa-fw')?> <span><?=lg('Посл. визит')?>: <?=ftime($account['DATE_VISIT'])?>
</div>
  
<?php

if ($account['SEX'] == 2){
  
  $sex = lg('Женский');
  
}else{
  
  $sex = lg('Мужской');
  
}

?>

<div class='profile_info_list'>
<?=icons('venus-mars', 20, 'fa-fw')?> <span><?=lg('Пол')?>: <?=$sex?>
</div>
  
</div>  
  
<?php

if (user('ID') > 0 && $account['ID'] == user('ID')){
  
  ?>
  <div class='list-menu'>
  <a class='btn-o' href='/account/form/?id=<?=$account['ID']?>&get=general_info&<?=TOKEN_URL?>'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  </div>
  <?
  
}

?>

</div>
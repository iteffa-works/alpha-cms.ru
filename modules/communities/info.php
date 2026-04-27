<?php
$comm = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Информация о сообществе %s', communities::name($comm['ID'])));
acms_header();
communities::blocked($comm['ID']);

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

$cat = db::get_string("SELECT `ID`,`NAME` FROM `COMMUNITIES_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$comm['ID_CATEGORY']]);

if (isset($cat['ID'])){
  
  $ct = '<a href="/m/communities/categories/?id='.$cat['ID'].'">'.tabs(lg($cat['NAME'])).'</a>';
  
}else{
  
  $ct = lg('Без категории');
  
}

if ($comm['PRIVATE'] == 0){
  
  $type = lg('Открытое сообщество');

}elseif ($comm['PRIVATE'] == 1){
  
  $type = lg('Анонимное сообщество');

}elseif ($comm['PRIVATE'] == 2){
  
  $type = lg('Сообщество по интересам');

}

?>
<div class='list' style='padding: 0px'>  
  
<div class='profile_info_list'>  
<?=icons('clock-o', 20, 'fa-fw')?> <span><?=lg('Дата основания')?>: <?=ftime($comm['TIME'])?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('user', 20, 'fa-fw')?> <span><?=lg('Основатель')?>: <?=user::login($comm['USER_ID'], 0, 1)?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('list', 20, 'fa-fw')?> <span><?=lg('Категория')?>: <?=$ct?></span>
</div>  
  
<div class='profile_info_list'>  
<?=icons('users', 20, 'fa-fw')?> <span><?=lg('Тип')?>: <?=$type?></span>
</div> 
  
<div class='profile_info_list'>  
<?=icons('bullhorn', 20, 'fa-fw')?> <span><?=lg('Девиз')?>: <?=tabs($comm['MOTTO'])?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('cutlery', 20, 'fa-fw')?> <span><?=lg('Интересы')?>: <?=tabs($comm['INTERESTS'])?></span>
</div> 
  
<div class='profile_info_list'>  
<?=icons('exclamation-triangle', 20, 'fa-fw')?> <span><?=lg('Правила')?>: <?=tabs($comm['RULES'])?></span>
</div>  
  
</div>
<?


back('/public/'.$comm['URL']);
acms_footer();